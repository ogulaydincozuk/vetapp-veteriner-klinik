<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Survey extends Model {
    protected $fillable = ['user_id','pet_id','appointment_id','rating','comment'];
    public function user()        { return $this->belongsTo(User::class); }
    public function pet()         { return $this->belongsTo(Pet::class); }
    public function appointment() { return $this->belongsTo(Appointment::class); }
}