<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeightRecord;
use App\Models\Pet;

class WeightController extends Controller {

    public function index() {
        $user = auth()->user();
        $pets = Pet::where('user_id', $user->id)->orderBy('pet_name')->get();

        $selectedPetId = request('pet_id');
        $selectedPet   = null;
        $weightRecords = collect();

        if ($selectedPetId) {
            $selectedPet = Pet::where('id', $selectedPetId)
                ->where('user_id', $user->id)->firstOrFail();
            $weightRecords = WeightRecord::where('pet_id', $selectedPet->id)
                ->orderBy('recorded_at')->get();
        }

        // Son ölçüm eklenen 5 hasta
        $recentWeights = WeightRecord::whereHas('pet', fn($q) => $q->where('user_id', $user->id))
            ->with('pet')->orderByDesc('recorded_at')->take(10)->get();

        return view('weight.index', compact('pets','selectedPet','weightRecords','recentWeights'));
    }

    public function store(Request $request) {
        $request->validate([
            'pet_id'      => 'required|exists:pets,id',
            'weight'      => 'required|numeric|min:0|max:999',
            'recorded_at' => 'required|date',
            'notes'       => 'nullable|string|max:200',
        ]);

        $pet = Pet::where('id', $request->pet_id)
            ->where('user_id', auth()->id())->firstOrFail();

        WeightRecord::create([
            'pet_id'      => $pet->id,
            'weight'      => $request->weight,
            'recorded_at' => $request->recorded_at,
            'notes'       => $request->notes,
        ]);

        // Hastanın mevcut kilosunu güncelle
        $pet->update(['weight' => $request->weight]);

        return back()->with('success', $pet->pet_name . ' için kilo kaydı eklendi.');
    }
}