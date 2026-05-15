<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model {
    protected $fillable = [
        'user_id','owner_name','owner_phone','pet_name',
        'species','breed','gender','birth_date','weight',
        'profile_photo','notes'
    ];

    protected $casts = ['birth_date' => 'date'];

    public function user()          { return $this->belongsTo(User::class); }
    public function appointments()  { return $this->hasMany(Appointment::class); }
    public function vaccines()      { return $this->hasMany(Vaccine::class); }
    public function weightRecords() { return $this->hasMany(WeightRecord::class)->orderBy('recorded_at'); }
    public function surgeries()     { return $this->hasMany(Surgery::class); }
}