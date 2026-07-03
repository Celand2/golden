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
        Schema::create('lumicash_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->unique(); // Numéro de téléphone Lumicash unique
            $table->string('name');              // Nom du compte Lumicash
            $table->timestamps();
        });

        // Insérer la configuration par défaut depuis les env variables
        $phone = env('LUMICASH_PHONE', '');
        $name = env('LUMICASH_NAME', '');

        if ($phone && $name) {
            \DB::table('lumicash_accounts')->insert([
                'phone' => $phone,
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lumicash_accounts');
    }
};
