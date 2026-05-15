<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmsUsage extends Model {
    protected $table = 'sms_usage'; // ← bunu ekle

    protected $fillable = ['user_id', 'total_sms', 'used_sms'];

    public function user() { return $this->belongsTo(User::class); }

    public function remaining(): int {
        return max(0, $this->total_sms - $this->used_sms);
    }

    public function percentage(): int {
        if ($this->total_sms === 0) return 0;
        return (int) round(($this->used_sms / $this->total_sms) * 100);
    }
}