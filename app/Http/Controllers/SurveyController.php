<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\Pet;
use App\Models\Appointment;

class SurveyController extends Controller {

    public function index() {
        $user = auth()->user();

        $surveys = Survey::with('pet')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(15);

        $avgRating    = Survey::where('user_id', $user->id)->avg('rating') ?? 0;
        $lowRatings   = Survey::where('user_id', $user->id)->where('rating', '<=', 2)->count();
        $totalSurveys = Survey::where('user_id', $user->id)->count();

        $ratingDist = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = Survey::where('user_id', $user->id)->where('rating', $i)->count();
            $ratingDist[$i] = [
                'count' => $count,
                'pct'   => $totalSurveys > 0 ? round($count / $totalSurveys * 100) : 0,
            ];
        }

        $pets = Pet::where('user_id', $user->id)->orderBy('pet_name')->get();
        $appointments = Appointment::with('pet')
            ->where('user_id', $user->id)
            ->where('status','completed')
            ->orderByDesc('appointment_date')
            ->take(20)->get();

        return view('surveys.index', compact(
            'surveys','avgRating','lowRatings','totalSurveys','ratingDist','pets','appointments'
        ));
    }

    public function store(Request $request) {
        $request->validate([
            'pet_id'         => 'required|exists:pets,id',
            'rating'         => 'required|integer|min:1|max:5',
            'comment'        => 'nullable|string|max:500',
            'appointment_id' => 'nullable|exists:appointments,id',
        ]);

        Survey::create([
            'user_id'        => auth()->id(),
            'pet_id'         => $request->pet_id,
            'appointment_id' => $request->appointment_id,
            'rating'         => $request->rating,
            'comment'        => $request->comment,
        ]);

        return back()->with('success', 'Anket kaydedildi.');
    }
}