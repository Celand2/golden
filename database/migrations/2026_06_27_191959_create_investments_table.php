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
    Schema::create('investments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('vip_plan_id')->constrained()->cascadeOnDelete();
        $table->decimal('amount', 15, 2);                        // montant investi
        $table->decimal('daily_gain', 15, 2);                    // amount * 7%
        $table->decimal('accumulated_gains', 15, 2)->default(0); // gains accumulés
        $table->decimal('total_claimed', 15, 2)->default(0);     // total déjà réclamé
        $table->enum('status', ['active', 'expired'])->default('active');
        $table->timestamp('expires_at');                         // + 180 jours
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
