<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surgery;
use App\Models\Pet;
use App\Models\Doctor;
use Carbon\Carbon;

class SurgeryController extends Controller {

    public function index() {
        $user = auth()->user();

        $upcoming = Surgery::with('pet')
            ->where('user_id', $user->id)
            ->where('status','scheduled')
            ->where('surgery_date', '>=', Carbon::today())
            ->orderBy('surgery_date')->get();

        $past = Surgery::with('pet')
            ->where('user_id', $user->id)
            ->where(function($q) {
                $q->where('status','completed')
                  ->orWhere('status','cancelled')
                  ->orWhere('surgery_date', '<', Carbon::today());
            })
            ->orderByDesc('surgery_date')
            ->paginate(10);

        $pets    = Pet::where('user_id', $user->id)->orderBy('pet_name')->get();
        $doctors = Doctor::where('user_id', $user->id)->where('is_active', true)->get();

        return view('surgeries.index', compact('upcoming','past','pets','doctors'));
    }

    public function store(Request $request) {
        $request->validate([
            'pet_id'       => 'required|exists:pets,id',
            'surgery_name' => 'required|string|max:150',
            'surgery_date' => 'required|date',
            'doctor_name'  => 'nullable|string|max:100',
            'pre_notes'    => 'nullable|string|max:500',
        ]);

        $pet = Pet::where('id', $request->pet_id)
            ->where('user_id', auth()->id())->firstOrFail();

        Surgery::create([
            'user_id'      => auth()->id(),
            'pet_id'       => $pet->id,
            'surgery_name' => $request->surgery_name,
            'surgery_date' => $request->surgery_date,
            'doctor_name'  => $request->doctor_name,
            'pre_notes'    => $request->pre_notes,
            'status'       => 'scheduled',
        ]);

        return back()->with('success', 'Ameliyat takvime eklendi.');
    }

    public function updateStatus(Request $request, Surgery $surgery) {
        abort_if($surgery->user_id !== auth()->id(), 403);
        $request->validate([
            'status'     => 'required|in:scheduled,completed,cancelled',
            'post_notes' => 'nullable|string|max:500',
        ]);
        $surgery->update([
            'status'     => $request->status,
            'post_notes' => $request->post_notes,
        ]);
        return back()->with('success', 'Ameliyat durumu güncellendi.');
    }
}