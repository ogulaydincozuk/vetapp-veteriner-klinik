<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Pet;
use App\Models\Doctor;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $appointments = Appointment::with('pet')
            ->where('user_id', $user->id)
            ->orderByDesc('appointment_date')
            ->orderBy('appointment_time')
            ->paginate(15);

        $todayAppointments = Appointment::with('pet')
            ->where('user_id', $user->id)
            ->whereDate('appointment_date', Carbon::today())
            ->orderBy('appointment_time')
            ->get();

        $upcomingAppointments = Appointment::with('pet')
            ->where('user_id', $user->id)
            ->whereDate('appointment_date', '>', Carbon::today())
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(10)
            ->get();

        $pets    = Pet::where('user_id', $user->id)->orderBy('pet_name')->get();
        $doctors = $user->isGold()
            ? Doctor::where('user_id', $user->id)->where('is_active', true)->get()
            : collect();

        return view('appointments.index', compact(
            'appointments', 'todayAppointments', 'upcomingAppointments', 'pets', 'doctors'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'pet_id'           => 'required|exists:pets,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'type'             => 'required|in:vaccine,checkup,surgery,xray,other',
            'notes'            => 'nullable|string|max:500',
        ]);

        // Bronze limit kontrolü
        if ($user->isBronze()) {
            $monthCount = Appointment::where('user_id', $user->id)
                ->whereMonth('appointment_date', Carbon::now()->month)
                ->whereYear('appointment_date', Carbon::now()->year)
                ->count();
            if ($monthCount >= 50) {
                return back()->with('error', 'Aylık 50 randevu limitine ulaştınız. Gümüş pakete geçin.');
            }
        }

        Appointment::create([
            'user_id'          => $user->id,
            'pet_id'           => $request->pet_id,
            'doctor_id'        => $request->doctor_id ?? null,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'type'             => $request->type,
            'status'           => 'pending',
            'notes'            => $request->notes,
        ]);

        return back()->with('success', 'Randevu başarıyla oluşturuldu.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        // Kullanıcı sadece kendi randevusunu güncelleyebilir
        abort_if($appointment->user_id !== auth()->id(), 403);

        $request->validate(['status' => 'required|in:pending,confirmed,completed,cancelled']);
        $appointment->update(['status' => $request->status]);

        return back()->with('success', 'Randevu durumu güncellendi.');
    }

    public function destroy(Appointment $appointment)
    {
        abort_if($appointment->user_id !== auth()->id(), 403);
        $appointment->delete();
        return back()->with('success', 'Randevu silindi.');
    }
}