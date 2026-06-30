<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::find(1);
        if ($user) {
            $user->update(['role' => 'admin']);
            echo "User ID 1 ({$user->name}) is now admin!\n";
        } else {
            echo "User ID 1 not found.\n";
        }
    }
}
