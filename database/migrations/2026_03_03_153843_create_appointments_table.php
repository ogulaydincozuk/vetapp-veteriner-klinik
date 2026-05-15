<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->nullable()->constrained()->nullOnDelete();
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->enum('type', ['vaccine','checkup','surgery','xray','other'])->default('checkup');
            $table->enum('status', ['pending','confirmed','completed','cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('appointments'); }
};