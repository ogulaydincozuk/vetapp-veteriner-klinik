<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

   protected $fillable = [
    'name', 'email', 'password',
    'clinic_name', 'phone', 'avatar',
    'subscription_plan', 'subscription_expires_at', 'is_active',
    // Yeni eklenenler:
    'clinic_address', 'clinic_city', 'clinic_website',
    'working_hours_start', 'working_hours_end',
    'working_saturday', 'working_sunday',
    'notify_whatsapp', 'notify_sms',
    'notify_appointment_reminder', 'notify_vaccine_reminder',
    'reminder_hours_before','slug',
];

protected $casts = [
    'email_verified_at'            => 'datetime',
    'subscription_expires_at'      => 'datetime',
    'is_active'                    => 'boolean',
    'password'                     => 'hashed',
    // Yeni eklenenler:
    'working_saturday'             => 'boolean',
    'working_sunday'               => 'boolean',
    'notify_whatsapp'              => 'boolean',
    'notify_sms'                   => 'boolean',
    'notify_appointment_reminder'  => 'boolean',
    'notify_vaccine_reminder'      => 'boolean',
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    

    // ── İLİŞKİLER ─────────────────────────────────────────────

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function surgeries()
    {
        return $this->hasMany(Surgery::class);
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }

    public function smsUsage()
    {
        return $this->hasOne(SmsUsage::class);
    }

    public function waitingList()
    {
        return $this->hasMany(WaitingList::class);
    }

    // ── PAKET YARDIMCILARI ────────────────────────────────────

    public function isBronze(): bool
    {
        return $this->subscription_plan === 'bronze';
    }

    public function isSilver(): bool
    {
        return $this->subscription_plan === 'silver';
    }

    public function isGold(): bool
    {
        return $this->subscription_plan === 'gold';
    }

    public function canAccess(string $plan): bool
    {
        $order = ['bronze' => 1, 'silver' => 2, 'gold' => 3];
        return ($order[$this->subscription_plan] ?? 0) >= ($order[$plan] ?? 0);
    }

    public function getDashboardRoute(): string
    {
        return match($this->subscription_plan) {
            'silver' => 'dashboard.silver',
            'gold'   => 'dashboard.gold',
            default  => 'dashboard.bronze',
        };
    }
}