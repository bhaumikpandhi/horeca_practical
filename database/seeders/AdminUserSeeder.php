<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::query()->where('email', 'admin@admin.com')->exists()) {
            User::factory()->create([
                'name' => 'Admin',
                'role' => 'admin',
                'email' => 'admin@admin.com',
            ]);
        }
    }
}
