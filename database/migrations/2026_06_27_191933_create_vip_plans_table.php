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
    Schema::create('vip_plans', function (Blueprint $table) {
        $table->id();
        $table->string('name');                              // Bronze, Silver...
        $table->decimal('min_amount', 15, 2);               // montant minimum
        $table->decimal('daily_rate', 5, 2)->default(7.00); // 7%
        $table->integer('duration_days')->default(180);      // 6 mois
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vip_plans');
    }
};
