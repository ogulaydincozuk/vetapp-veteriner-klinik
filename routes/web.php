<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\VaccineController;
use App\Http\Controllers\WeightController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\WaitingListController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\SurgeryController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\PublicBookingController;

// ── Landing page ──────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// ── Auth ──────────────────────────────────────────────────
Route::get('/giris', [AuthController::class, 'showLogin'])->name('login');
Route::post('/giris', [AuthController::class, 'login'])->name('login.post');
Route::post('/cikis', [AuthController::class, 'logout'])->name('logout');

// ── Kayıt ─────────────────────────────────────────────────
Route::get('/kayit', [RegisterController::class, 'show'])->name('register');
Route::post('/kayit', [RegisterController::class, 'store'])->name('register.post');
Route::get('/kayit/slug-kontrol', [RegisterController::class, 'checkSlug'])->name('register.slug-check');
// Demo Talebi
Route::post('/demo-talebi', [HomeController::class, 'storeDemo'])->name('demo.store');
// ── Şifremi Unuttum ───────────────────────────────────────
Route::get('/sifremi-unuttum', [PasswordResetController::class, 'showForm'])->name('password.request');
Route::post('/sifremi-unuttum', [PasswordResetController::class, 'sendLink'])->name('password.email');
Route::get('/sifre-sifirla/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
Route::post('/sifre-sifirla', [PasswordResetController::class, 'reset'])->name('password.update');

// ── Slot API ──────────────────────────────────────────────
Route::get('/api/slots/{slug}', function (string $slug) {
    $clinic = \App\Models\User::where('slug', $slug)->firstOrFail();
    $date   = request('date', date('Y-m-d'));
    $taken  = \App\Models\Appointment::where('user_id', $clinic->id)
        ->whereDate('appointment_date', $date)
        ->whereIn('status', ['pending', 'confirmed'])
        ->pluck('appointment_time')
        ->map(fn($t) => \Carbon\Carbon::parse($t)->format('H:i'))
        ->toArray();
    return response()->json(['taken' => $taken]);
});

// ── Dashboard & Tüm Korumalı Sayfalar ────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard/bronze', [DashboardController::class, 'bronze'])
        ->middleware('package:bronze')->name('dashboard.bronze');
    Route::get('/dashboard/silver', [DashboardController::class, 'silver'])
        ->middleware('package:silver')->name('dashboard.silver');
    Route::get('/dashboard/gold', [DashboardController::class, 'gold'])
        ->middleware('package:gold')->name('dashboard.gold');

    // Randevular
    Route::get('/randevular', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/randevular', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::patch('/randevular/{appointment}/durum', [AppointmentController::class, 'updateStatus'])->name('appointments.status');
    Route::delete('/randevular/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    // Hastalar
    Route::get('/hastalar', [PetController::class, 'index'])->name('pets.index');
    Route::post('/hastalar', [PetController::class, 'store'])->name('pets.store');
    Route::get('/hastalar/{pet}', [PetController::class, 'show'])->name('pets.show');
    Route::patch('/hastalar/{pet}', [PetController::class, 'update'])->name('pets.update');
    Route::delete('/hastalar/{pet}', [PetController::class, 'destroy'])->name('pets.destroy');

    // Aşı Takibi
    Route::get('/asi-takibi', [VaccineController::class, 'index'])->name('vaccines.index');
    Route::post('/asi-takibi', [VaccineController::class, 'store'])->name('vaccines.store');

    // Kilo Takibi (Silver+)
    Route::get('/kilo-takibi', [WeightController::class, 'index'])->middleware('package:silver')->name('weight.index');
    Route::post('/kilo-takibi', [WeightController::class, 'store'])->middleware('package:silver')->name('weight.store');

    // Duyurular (Silver+)
    Route::get('/duyurular', [AnnouncementController::class, 'index'])->middleware('package:silver')->name('announcements.index');
    Route::post('/duyurular', [AnnouncementController::class, 'store'])->middleware('package:silver')->name('announcements.store');

    // Bekleme Listesi (Silver+)
    Route::get('/bekleme-listesi', [WaitingListController::class, 'index'])->middleware('package:silver')->name('waiting.index');
    Route::post('/bekleme-listesi', [WaitingListController::class, 'store'])->middleware('package:silver')->name('waiting.store');
    Route::patch('/bekleme-listesi/{item}/durum', [WaitingListController::class, 'updateStatus'])->middleware('package:silver')->name('waiting.status');
    Route::delete('/bekleme-listesi/{item}', [WaitingListController::class, 'destroy'])->middleware('package:silver')->name('waiting.destroy');

    // Anketler (Silver+)
    Route::get('/anketler', [SurveyController::class, 'index'])->middleware('package:silver')->name('surveys.index');
    Route::post('/anketler', [SurveyController::class, 'store'])->middleware('package:silver')->name('surveys.store');

    // Ameliyat Takvimi (Gold)
    Route::get('/ameliyat-takvimi', [SurgeryController::class, 'index'])->middleware('package:gold')->name('surgeries.index');
    Route::post('/ameliyat-takvimi', [SurgeryController::class, 'store'])->middleware('package:gold')->name('surgeries.store');
    Route::patch('/ameliyat-takvimi/{surgery}/durum', [SurgeryController::class, 'updateStatus'])->middleware('package:gold')->name('surgeries.status');

    // Doktor Yönetimi (Gold)
    Route::get('/doktorlar', [DoctorController::class, 'index'])->middleware('package:gold')->name('doctors.index');
    Route::post('/doktorlar', [DoctorController::class, 'store'])->middleware('package:gold')->name('doctors.store');
    Route::patch('/doktorlar/{doctor}', [DoctorController::class, 'update'])->middleware('package:gold')->name('doctors.update');
    Route::delete('/doktorlar/{doctor}', [DoctorController::class, 'destroy'])->middleware('package:gold')->name('doctors.destroy');

    // Tedavi Planları (Gold)
    Route::get('/tedavi-planlari', [TreatmentController::class, 'index'])->middleware('package:gold')->name('treatments.index');
    Route::post('/tedavi-planlari', [TreatmentController::class, 'store'])->middleware('package:gold')->name('treatments.store');

    // Gelişmiş Raporlar (Gold)
    Route::get('/raporlar', [ReportController::class, 'index'])->middleware('package:gold')->name('reports.index');

    // Ayarlar
    Route::get('/ayarlar', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/ayarlar/klinik', [SettingsController::class, 'updateClinic'])->name('settings.clinic');
    Route::post('/ayarlar/saatler', [SettingsController::class, 'updateHours'])->name('settings.hours');
    Route::post('/ayarlar/bildirimler', [SettingsController::class, 'updateNotifications'])->name('settings.notifications');
    Route::post('/ayarlar/sifre', [SettingsController::class, 'updatePassword'])->name('settings.password');
});

// ── Public Randevu — EN SONDA OLMALI ─────────────────────
Route::get('/{slug}', [PublicBookingController::class, 'show'])->name('booking.show')
    ->where('slug', '^(?!giris|cikis|kayit|sifremi-unuttum|sifre-sifirla|api|dashboard|randevular|hastalar|asi-takibi|kilo-takibi|duyurular|bekleme-listesi|anketler|ameliyat-takvimi|doktorlar|tedavi-planlari|raporlar|ayarlar)[a-z0-9\-]+$');
Route::post('/{slug}/randevu', [PublicBookingController::class, 'store'])->name('booking.store')
    ->where('slug', '[a-z0-9\-]+');
Route::get('/{slug}/randevu/{appointment}/onay', [PublicBookingController::class, 'confirm'])->name('booking.confirm')
    ->where('slug', '[a-z0-9\-]+');
    