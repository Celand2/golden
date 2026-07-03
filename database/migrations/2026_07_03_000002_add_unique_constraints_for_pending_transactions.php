<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Note: MariaDB doesn't support WHERE clause in UNIQUE INDEX
     * Using application-level validation for now (enforced in WithdrawalService, DepositService)
     * Add trigger-based constraints if needed for production
     */
    public function up(): void
    {
        // Simple index pour performance sur les recherches de pending transactions
        Schema::table('transactions', function (Blueprint $table) {
            $table->index(['user_id', 'type', 'status'], 'idx_user_type_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('idx_user_type_status');
        });
    }
};

