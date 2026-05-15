<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TreatmentPlan extends Model {
    protected $fillable = [
        'user_id','pet_id','title','description',
        'start_date','end_date','status','doctor_name'
    ];
    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];
    public function user() { return $this->belongsTo(User::class); }
    public function pet()  { return $this->belongsTo(Pet::class); }

    public function getStatusLabel(): string {
        return match($this->status) {
            'active'    => 'Aktif',
            'completed' => 'Tamamlandı',
            'cancelled' => 'İptal',
            default     => '-',
        };
    }
}