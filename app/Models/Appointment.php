<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model {
    protected $fillable = [
        'user_id','pet_id','doctor_id','appointment_date',
        'appointment_time','type','status','notes'
    ];

    protected $casts = ['appointment_date' => 'date'];

    public function user()   { return $this->belongsTo(User::class); }
    public function pet()    { return $this->belongsTo(Pet::class); }
    public function doctor() { return $this->belongsTo(Doctor::class); }

    public function getTypeLabel(): string {
        return match($this->type) {
            'vaccine'  => 'Aşı',
            'checkup'  => 'Kontrol',
            'surgery'  => 'Ameliyat',
            'xray'     => 'Röntgen',
            default    => 'Diğer',
        };
    }

    public function getStatusLabel(): string {
        return match($this->status) {
            'pending'   => 'Bekliyor',
            'confirmed' => 'Onaylı',
            'completed' => 'Tamamlandı',
            'cancelled' => 'İptal',
            default     => '-',
        };
    }
}