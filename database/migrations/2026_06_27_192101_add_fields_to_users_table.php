<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('phone')->nullable();
        $table->string('referral_code')->unique();
        $table->foreignId('referred_by')->nullable()->constrained('users')->nullOnDelete();
        $table->enum('role', ['standard', 'premium', 'admin'])->default('standard');
        $table->decimal('wallet_balance', 15, 2)->default(0);
        $table->integer('referral_count')->default(0); // compte les filleuls L1
        $table->boolean('is_active')->default(true);
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'phone', 'referral_code', 'referred_by',
            'role', 'wallet_balance', 'referral_count', 'is_active'
        ]);
    });
}

    /**
     * Reverse the migrations.
     */
};