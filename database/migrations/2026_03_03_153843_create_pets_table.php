<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('owner_name');
            $table->string('owner_phone');
            $table->string('pet_name');
            $table->string('species'); // kedi, köpek, kuş vs
            $table->string('breed')->nullable();
            $table->enum('gender', ['male', 'female', 'unknown'])->default('unknown');
            $table->date('birth_date')->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->string('profile_photo')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pets'); }
};