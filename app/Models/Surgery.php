<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Surgery extends Model {
    protected $fillable = ['user_id','pet_id','surgery_name','surgery_date','pre_notes','post_notes','status','doctor_name'];
    protected $casts    = ['surgery_date'=>'datetime'];
    public function user() { return $this->belongsTo(User::class); }
    public function pet()  { return $this->belongsTo(Pet::class); }
}
