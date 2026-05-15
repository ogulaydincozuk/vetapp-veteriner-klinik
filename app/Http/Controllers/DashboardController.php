<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\{Appointment, Pet, SmsUsage, WaitingList, Survey, Surgery};
use Carbon\Carbon;

class DashboardController extends Controller {

    // ── Ortak veri yardımcısı ──────────────────────────────────
    private function baseData(): array {
        $user  = auth()->user();
        $today = Carbon::today();

        $todayAppointments = Appointment::with('pet')
            ->where('user_id', $user->id)
            ->whereDate('appointment_date', $today)
            ->orderBy('appointment_time')
            ->get();

        $monthAppointments = Appointment::where('user_id', $user->id)
            ->whereMonth('appointment_date', $today->month)
            ->whereYear('appointment_date', $today->year)
            ->count();

        $totalPets = Pet::where('user_id', $user->id)->count();

        $recentActivity = Appointment::with('pet')
            ->where('user_id', $user->id)
            ->latest()
            ->take(8)
            ->get();

        return compact('user','today','todayAppointments','monthAppointments','totalPets','recentActivity');
    }

    // ── BRONZE ────────────────────────────────────────────────
    public function bronze() {
        $data     = $this->baseData();
        $user     = $data['user'];
        $smsUsage = SmsUsage::firstOrCreate(['user_id' => $user->id], ['total_sms'=>0,'used_sms'=>0]);

        return view('dashboard.bronze', array_merge($data, compact('smsUsage')));
    }

    // ── SILVER ────────────────────────────────────────────────
    public function silver() {
        $data    = $this->baseData();
        $user    = $data['user'];

        $smsUsage      = SmsUsage::firstOrCreate(['user_id' => $user->id], ['total_sms'=>0,'used_sms'=>0]);
        $waitingCount  = WaitingList::where('user_id', $user->id)->where('status','waiting')->count();
        $avgRating     = Survey::where('user_id', $user->id)->avg('rating') ?? 0;
        $recentSurveys = Survey::with('pet')->where('user_id', $user->id)->latest()->take(5)->get();

        $birthdayPets = Pet::where('user_id', $user->id)
            ->whereNotNull('birth_date')
            ->get()
            ->filter(fn($p) => $p->birth_date->format('m-d') >= Carbon::today()->format('m-d')
                && $p->birth_date->format('m-d') <= Carbon::today()->addDays(7)->format('m-d'))
            ->count();

        return view('dashboard.silver', array_merge($data, compact(
            'smsUsage','waitingCount','avgRating','recentSurveys','birthdayPets'
        )));
    }

    // ── GOLD ──────────────────────────────────────────────────
    public function gold() {
        $data    = $this->baseData();
        $user    = $data['user'];

        $waitingCount   = WaitingList::where('user_id', $user->id)->where('status','waiting')->count();
        $avgRating      = Survey::where('user_id', $user->id)->avg('rating') ?? 0;
        $recentSurveys  = Survey::with('pet')->where('user_id', $user->id)->latest()->take(5)->get();
        $upcomingSurgeries = Surgery::with('pet')
            ->where('user_id', $user->id)
            ->where('status','scheduled')
            ->where('surgery_date', '>=', Carbon::today())
            ->orderBy('surgery_date')
            ->take(5)
            ->get();

        $birthdayPets = Pet::where('user_id', $user->id)
            ->whereNotNull('birth_date')
            ->get()
            ->filter(fn($p) => $p->birth_date->format('m-d') >= Carbon::today()->format('m-d')
                && $p->birth_date->format('m-d') <= Carbon::today()->addDays(7)->format('m-d'))
            ->count();

        // Grafik için son 6 ay randevu sayıları
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::today()->subMonths($i);
            $chartData[] = [
                'label' => $month->locale('tr')->isoFormat('MMM'),
                'count' => Appointment::where('user_id', $user->id)
                    ->whereMonth('appointment_date', $month->month)
                    ->whereYear('appointment_date', $month->year)
                    ->count(),
            ];
        }

        return view('dashboard.gold', array_merge($data, compact(
            'waitingCount','avgRating','recentSurveys',
            'upcomingSurgeries','birthdayPets','chartData'
        )));
    }
}