<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pet;
use App\Models\Appointment;
use Carbon\Carbon;

class PublicBookingController extends Controller
{
    public function show(string $slug)
    {
        $clinic = User::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Bu haftaki müsait slotları hesapla
        $availableSlots = $this->getAvailableSlots($clinic);

        // Bu ayki randevu sayısı (limit kontrolü için)
        $monthlyCount = Appointment::where('user_id', $clinic->id)
            ->whereMonth('appointment_date', Carbon::now()->month)
            ->whereYear('appointment_date', Carbon::now()->year)
            ->count();

        $limit = match($clinic->subscription_plan) {
            'bronze' => 50,
            'silver' => 150,
            default  => PHP_INT_MAX,
        };

        $isFull = $monthlyCount >= $limit;

        return view('booking.show', compact('clinic','availableSlots','isFull'));
    }

    public function store(Request $request, string $slug)
    {
        $clinic = User::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $request->validate([
            'owner_name'       => 'required|string|max:100',
            'owner_phone'      => 'required|string|max:20',
            'pet_name'         => 'required|string|max:100',
            'species'          => 'required|string|max:50',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'type'             => 'required|in:vaccine,checkup,surgery,xray,other',
            'notes'            => 'nullable|string|max:300',
        ]);

        // Limit kontrolü
        $monthlyCount = Appointment::where('user_id', $clinic->id)
            ->whereMonth('appointment_date', Carbon::now()->month)
            ->count();
        $limit = match($clinic->subscription_plan) {
            'bronze' => 50, 'silver' => 150, default => PHP_INT_MAX,
        };
        if ($monthlyCount >= $limit) {
            return back()->with('error', 'Bu klinik bu ay için randevu kapasitesine ulaşmıştır.');
        }

        // Aynı saatte randevu var mı?
        $conflict = Appointment::where('user_id', $clinic->id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->whereIn('status', ['pending','confirmed'])
            ->exists();
        if ($conflict) {
            return back()->with('error', 'Seçtiğiniz saat dolu. Lütfen başka bir saat seçin.')
                ->withInput();
        }

        // Hasta kaydı oluştur veya bul
        $pet = Pet::firstOrCreate(
            [
                'user_id'     => $clinic->id,
                'owner_phone' => $request->owner_phone,
                'pet_name'    => $request->pet_name,
            ],
            [
                'owner_name'  => $request->owner_name,
                'species'     => $request->species,
                'gender'      => 'unknown',
            ]
        );

        $appointment = Appointment::create([
            'user_id'          => $clinic->id,
            'pet_id'           => $pet->id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'type'             => $request->type,
            'status'           => 'pending',
            'notes'            => $request->notes,
        ]);

        return redirect()->route('booking.confirm', [
            'slug'        => $slug,
            'appointment' => $appointment->id,
        ]);
    }

    public function confirm(string $slug, int $appointment)
    {
        $clinic = User::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $appt   = Appointment::with('pet')
            ->where('id', $appointment)
            ->where('user_id', $clinic->id)
            ->firstOrFail();

        return view('booking.confirm', compact('clinic','appt'));
    }

    private function getAvailableSlots(User $clinic): array
    {
        $start = $clinic->working_hours_start ?? '09:00';
        $end   = $clinic->working_hours_end   ?? '18:00';

        $slots = [];
        $current = Carbon::parse($start);
        $endTime = Carbon::parse($end);

        while ($current < $endTime) {
            $slots[] = $current->format('H:i');
            $current->addMinutes(30);
        }

        return $slots;
    }
}