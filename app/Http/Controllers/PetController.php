<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\Appointment;
use App\Models\Vaccine;

class PetController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $search = request('search');

        $pets = Pet::where('user_id', $user->id)
            ->when($search, function ($q) use ($search) {
                $q->where('pet_name', 'like', "%$search%")
                  ->orWhere('owner_name', 'like', "%$search%")
                  ->orWhere('species', 'like', "%$search%");
            })
            ->orderBy('pet_name')
            ->paginate(12);

        return view('pets.index', compact('pets', 'search'));
    }

    public function show(Pet $pet)
    {
        abort_if($pet->user_id !== auth()->id(), 403);

        $appointments = Appointment::where('pet_id', $pet->id)
            ->orderByDesc('appointment_date')
            ->get();

        $vaccines = Vaccine::where('pet_id', $pet->id)
            ->orderByDesc('vaccine_date')
            ->get();

        $overdueVaccines = $vaccines->filter(
            fn($v) => $v->next_date && $v->next_date->isPast()
        );

        return view('pets.show', compact('pet', 'appointments', 'vaccines', 'overdueVaccines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'owner_name'  => 'required|string|max:100',
            'owner_phone' => 'required|string|max:20',
            'pet_name'    => 'required|string|max:100',
            'species'     => 'required|string|max:50',
            'breed'       => 'nullable|string|max:100',
            'gender'      => 'nullable|in:male,female,unknown',
            'birth_date'  => 'nullable|date',
            'weight'      => 'nullable|numeric|min:0|max:999',
            'notes'       => 'nullable|string|max:1000',
        ]);

        Pet::create([
            'user_id'     => auth()->id(),
            'owner_name'  => $request->owner_name,
            'owner_phone' => $request->owner_phone,
            'pet_name'    => $request->pet_name,
            'species'     => $request->species,
            'breed'       => $request->breed,
            'gender'      => $request->gender ?? 'unknown',
            'birth_date'  => $request->birth_date,
            'weight'      => $request->weight,
            'notes'       => $request->notes,
        ]);

        return back()->with('success', $request->pet_name . ' başarıyla eklendi.');
    }

    public function update(Request $request, Pet $pet)
    {
        abort_if($pet->user_id !== auth()->id(), 403);

        $request->validate([
            'owner_name'  => 'required|string|max:100',
            'owner_phone' => 'required|string|max:20',
            'pet_name'    => 'required|string|max:100',
            'species'     => 'required|string|max:50',
            'weight'      => 'nullable|numeric|min:0|max:999',
        ]);

        $pet->update($request->only([
            'owner_name','owner_phone','pet_name',
            'species','breed','gender','birth_date','weight','notes'
        ]));

        return back()->with('success', 'Hasta bilgileri güncellendi.');
    }

    public function destroy(Pet $pet)
    {
        abort_if($pet->user_id !== auth()->id(), 403);
        $pet->delete();
        return redirect()->route('pets.index')->with('success', 'Hasta silindi.');
    }
}