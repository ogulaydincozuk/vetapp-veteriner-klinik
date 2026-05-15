<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Survey;

class DoctorController extends Controller {

    public function index() {
        $user = auth()->user();

        $doctors = Doctor::where('user_id', $user->id)
            ->withCount('appointments')
            ->orderBy('name')->get();

        return view('doctors.index', compact('doctors'));
    }

    public function store(Request $request) {
        $request->validate([
            'name'      => 'required|string|max:100',
            'specialty' => 'nullable|string|max:100',
            'phone'     => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:100',
        ]);

        Doctor::create([
            'user_id'   => auth()->id(),
            'name'      => $request->name,
            'specialty' => $request->specialty,
            'phone'     => $request->phone,
            'email'     => $request->email,
            'is_active' => true,
        ]);

        return back()->with('success', $request->name . ' eklendi.');
    }

    public function update(Request $request, Doctor $doctor) {
        abort_if($doctor->user_id !== auth()->id(), 403);
        $request->validate([
            'name'      => 'required|string|max:100',
            'specialty' => 'nullable|string|max:100',
            'phone'     => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:100',
            'is_active' => 'boolean',
        ]);
        $doctor->update($request->only(['name','specialty','phone','email','is_active']));
        return back()->with('success', 'Doktor bilgileri güncellendi.');
    }

    public function destroy(Doctor $doctor) {
        abort_if($doctor->user_id !== auth()->id(), 403);
        $doctor->update(['is_active' => false]);
        return back()->with('success', 'Doktor pasif yapıldı.');
    }
}