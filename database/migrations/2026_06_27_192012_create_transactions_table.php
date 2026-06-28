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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->enum('type', ['deposit', 'withdrawal', 'claim', 'commission']);
        $table->decimal('amount', 15, 2);
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->string('phone')->nullable();       // numéro Lumicash
        $table->string('provider')->default('lumicash');
        $table->text('note')->nullable();          // note admin
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
