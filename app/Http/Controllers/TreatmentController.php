<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TreatmentPlan;
use App\Models\Pet;
use App\Models\Doctor;

class TreatmentController extends Controller {

    public function index() {
        $user = auth()->user();

        $active = TreatmentPlan::with('pet')
            ->where('user_id', $user->id)
            ->where('status','active')
            ->orderBy('start_date')->get();

        $completed = TreatmentPlan::with('pet')
            ->where('user_id', $user->id)
            ->whereIn('status',['completed','cancelled'])
            ->orderByDesc('updated_at')
            ->paginate(10);

        $pets    = Pet::where('user_id', $user->id)->orderBy('pet_name')->get();
        $doctors = Doctor::where('user_id', $user->id)->where('is_active',true)->get();

        return view('treatments.index', compact('active','completed','pets','doctors'));
    }

    public function store(Request $request) {
        $request->validate([
            'pet_id'      => 'required|exists:pets,id',
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after:start_date',
            'doctor_name' => 'nullable|string|max:100',
        ]);

        $pet = Pet::where('id', $request->pet_id)
            ->where('user_id', auth()->id())->firstOrFail();

        TreatmentPlan::create([
            'user_id'     => auth()->id(),
            'pet_id'      => $pet->id,
            'title'       => $request->title,
            'description' => $request->description,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'doctor_name' => $request->doctor_name,
            'status'      => 'active',
        ]);

        return back()->with('success', 'Tedavi planı oluşturuldu.');
    }
}