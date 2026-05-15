<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class WeightRecord extends Model {
    protected $fillable = ['pet_id','weight','recorded_at','notes'];
    protected $casts    = ['recorded_at'=>'date'];
    public function pet() { return $this->belongsTo(Pet::class); }
}