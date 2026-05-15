<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WaitingList extends Model {
    protected $table = 'waiting_list'; // ← bunu ekle

    protected $fillable = [
        'user_id',
        'owner_name',
        'owner_phone',
        'pet_name',
        'preferred_date',
        'reason',
        'status',
    ];

    protected $casts = [
        'preferred_date' => 'date',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}