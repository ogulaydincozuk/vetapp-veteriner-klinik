<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Pet;
use App\Models\Survey;
use App\Models\Doctor;
use Carbon\Carbon;

class ReportController extends Controller {

    public function index() {
        $user   = auth()->user();
        $period = request('period', 'month');

        $startDate = match($period) {
            'week'    => Carbon::now()->startOfWeek(),
            'month'   => Carbon::now()->startOfMonth(),
            '3months' => Carbon::now()->subMonths(3),
            '6months' => Carbon::now()->subMonths(6),
            'year'    => Carbon::now()->startOfYear(),
            default   => Carbon::now()->startOfMonth(),
        };

        // Toplam randevular
        $totalAppointments = Appointment::where('user_id', $user->id)
            ->where('appointment_date', '>=', $startDate)->count();

        $completedAppointments = Appointment::where('user_id', $user->id)
            ->where('appointment_date', '>=', $startDate)
            ->where('status','completed')->count();

        $cancelledAppointments = Appointment::where('user_id', $user->id)
            ->where('appointment_date', '>=', $startDate)
            ->where('status','cancelled')->count();

        // Ziyaret türü dağılımı
        $typeDist = Appointment::where('user_id', $user->id)
            ->where('appointment_date', '>=', $startDate)
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')->pluck('count','type');

        // Hayvan türü dağılımı
        $speciesDist = Pet::where('user_id', $user->id)
            ->selectRaw('species, count(*) as count')
            ->groupBy('species')
            ->orderByDesc('count')
            ->get();

        // Yeni kod
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
        $month = Carbon::now()->subMonths($i);
        $monthlyTrend[] = [
        'label' => $month->locale('tr')->isoFormat('MMM YY'),
        'count' => (int) Appointment::where('user_id', $user->id)
            ->whereMonth('appointment_date', $month->month)
            ->whereYear('appointment_date', $month->year)
            ->count(),
    ];
}

        // Memnuniyet
        $avgRating    = Survey::where('user_id', $user->id)->avg('rating') ?? 0;
        $totalSurveys = Survey::where('user_id', $user->id)->count();

        // En çok gelen hastalar
        $topPets = Appointment::where('user_id', $user->id)
            ->selectRaw('pet_id, count(*) as visit_count')
            ->groupBy('pet_id')
            ->orderByDesc('visit_count')
            ->take(10)
            ->with('pet')
            ->get();

        // Doktor performansı
        $doctorStats = Doctor::where('user_id', $user->id)
            ->withCount(['appointments as total_appointments' => function($q) use ($startDate) {
                $q->where('appointment_date', '>=', $startDate);
            }])->get();

        return view('reports.index', compact(
            'period','totalAppointments','completedAppointments',
            'cancelledAppointments','typeDist','speciesDist',
            'monthlyTrend','avgRating','totalSurveys','topPets','doctorStats'
        ));
    }
}