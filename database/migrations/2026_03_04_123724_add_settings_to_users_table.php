<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('clinic_address')->nullable();
            $table->string('clinic_city')->nullable();
            $table->string('clinic_website')->nullable();
            $table->string('working_hours_start')->nullable()->default('09:00');
            $table->string('working_hours_end')->nullable()->default('18:00');
            $table->boolean('working_saturday')->default(true);
            $table->boolean('working_sunday')->default(false);
            $table->boolean('notify_whatsapp')->default(true);
            $table->boolean('notify_sms')->default(false);
            $table->boolean('notify_appointment_reminder')->default(true);
            $table->boolean('notify_vaccine_reminder')->default(true);
            $table->integer('reminder_hours_before')->default(24);
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'clinic_address','clinic_city','clinic_website',
                'working_hours_start','working_hours_end',
                'working_saturday','working_sunday',
                'notify_whatsapp','notify_sms',
                'notify_appointment_reminder','notify_vaccine_reminder',
                'reminder_hours_before',
            ]);
        });
    }
};