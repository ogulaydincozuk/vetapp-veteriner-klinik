<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Vaccine extends Model {
    protected $fillable = ['pet_id','vaccine_name','vaccine_date','next_date','administered_by'];
    protected $casts    = ['vaccine_date'=>'date','next_date'=>'date'];
    public function pet() { return $this->belongsTo(Pet::class); }
}