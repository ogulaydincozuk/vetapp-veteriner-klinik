<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sms_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('total_sms')->default(0);
            $table->integer('used_sms')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('sms_usage'); }
};