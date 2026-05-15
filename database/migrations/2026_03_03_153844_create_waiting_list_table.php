<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('waiting_list', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('owner_name');
            $table->string('owner_phone');
            $table->string('pet_name');
            $table->date('preferred_date')->nullable();
            $table->string('reason')->nullable();
            $table->enum('status', ['waiting','contacted','booked'])->default('waiting');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('waiting_list'); }
};