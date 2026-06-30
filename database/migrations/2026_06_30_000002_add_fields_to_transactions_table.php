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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('proof_path')->nullable()->after('payment_proof');
            $table->string('recipient_phone')->nullable()->after('proof_path');
            $table->string('recipient_name')->nullable()->after('recipient_phone');
            $table->text('rejection_reason')->nullable()->after('recipient_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['proof_path', 'recipient_phone', 'recipient_name', 'rejection_reason']);
        });
    }
};
