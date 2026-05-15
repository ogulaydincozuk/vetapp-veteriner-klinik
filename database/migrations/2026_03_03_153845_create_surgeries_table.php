<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('surgeries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->string('surgery_name');
            $table->dateTime('surgery_date');
            $table->text('pre_notes')->nullable();
            $table->text('post_notes')->nullable();
            $table->enum('status', ['scheduled','completed','cancelled'])->default('scheduled');
            $table->string('doctor_name')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('surgeries'); }
};