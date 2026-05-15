<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('clinic_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('subscription_plan', ['bronze', 'silver', 'gold'])->default('bronze');
            $table->timestamp('subscription_expires_at')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['clinic_name','phone','avatar','subscription_plan','subscription_expires_at','is_active']);
        });
    }
};