<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('settings.index', compact('user'));
    }

    // Klinik bilgileri güncelle
    public function updateClinic(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:100',
            'clinic_name'      => 'required|string|max:150',
            'phone'            => 'nullable|string|max:20',
            'clinic_address'   => 'nullable|string|max:300',
            'clinic_city'      => 'nullable|string|max:100',
            'clinic_website'   => 'nullable|url|max:200',
        ]);

        auth()->user()->update($request->only([
            'name', 'clinic_name', 'phone',
            'clinic_address', 'clinic_city', 'clinic_website',
        ]));

        return back()->with('success_clinic', 'Klinik bilgileri güncellendi.');
    }

    // Çalışma saatleri güncelle
    public function updateHours(Request $request)
    {
        $request->validate([
            'working_hours_start' => 'required',
            'working_hours_end'   => 'required',
        ]);

        auth()->user()->update([
            'working_hours_start' => $request->working_hours_start,
            'working_hours_end'   => $request->working_hours_end,
            'working_saturday'    => $request->boolean('working_saturday'),
            'working_sunday'      => $request->boolean('working_sunday'),
        ]);

        return back()->with('success_hours', 'Çalışma saatleri güncellendi.');
    }

    // Bildirim ayarları güncelle
    public function updateNotifications(Request $request)
    {
        auth()->user()->update([
            'notify_whatsapp'             => $request->boolean('notify_whatsapp'),
            'notify_sms'                  => $request->boolean('notify_sms'),
            'notify_appointment_reminder' => $request->boolean('notify_appointment_reminder'),
            'notify_vaccine_reminder'     => $request->boolean('notify_vaccine_reminder'),
            'reminder_hours_before'       => $request->integer('reminder_hours_before', 24),
        ]);

        return back()->with('success_notifications', 'Bildirim ayarları güncellendi.');
    }

    // Şifre değiştir
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Mevcut şifre hatalı.'])
                         ->with('tab', 'password');
        }

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success_password', 'Şifre başarıyla değiştirildi.');
    }
}