<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('vaccines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->string('vaccine_name');
            $table->date('vaccine_date');
            $table->date('next_date')->nullable();
            $table->string('administered_by')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('vaccines'); }
};