<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vaccine;
use App\Models\Pet;
use Carbon\Carbon;

class VaccineController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $pets = Pet::where('user_id', $user->id)->orderBy('pet_name')->get();

        // Gecikmiş aşılar
        $overdueVaccines = Vaccine::whereHas('pet', fn($q) => $q->where('user_id', $user->id))
            ->whereNotNull('next_date')
            ->where('next_date', '<', Carbon::today())
            ->with('pet')
            ->orderBy('next_date')
            ->get();

        // Bu ay yaklaşan aşılar
        $upcomingVaccines = Vaccine::whereHas('pet', fn($q) => $q->where('user_id', $user->id))
            ->whereNotNull('next_date')
            ->whereBetween('next_date', [Carbon::today(), Carbon::today()->addDays(30)])
            ->with('pet')
            ->orderBy('next_date')
            ->get();

        // Tüm aşılar
        $allVaccines = Vaccine::whereHas('pet', fn($q) => $q->where('user_id', $user->id))
            ->with('pet')
            ->orderByDesc('vaccine_date')
            ->paginate(15);

        return view('vaccines.index', compact(
            'pets', 'overdueVaccines', 'upcomingVaccines', 'allVaccines'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pet_id'          => 'required|exists:pets,id',
            'vaccine_name'    => 'required|string|max:100',
            'vaccine_date'    => 'required|date',
            'next_date'       => 'nullable|date|after:vaccine_date',
            'administered_by' => 'nullable|string|max:100',
        ]);

        // Güvenlik: pet bu kullanıcıya ait mi?
        $pet = Pet::where('id', $request->pet_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        Vaccine::create([
            'pet_id'          => $pet->id,
            'vaccine_name'    => $request->vaccine_name,
            'vaccine_date'    => $request->vaccine_date,
            'next_date'       => $request->next_date,
            'administered_by' => $request->administered_by,
        ]);

        return back()->with('success', 'Aşı kaydı eklendi.');
    }
}