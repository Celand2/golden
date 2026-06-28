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
    Schema::create('referral_commissions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('referred_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
        $table->integer('level');                  // 1, 2 ou 3
        $table->decimal('rate', 5, 2);             // 9, 2 ou 1
        $table->decimal('amount', 15, 2);          // montant commission
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_commissions');
    }
};
