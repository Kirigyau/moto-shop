<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(ProductSeeder::class);

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin'],
            [
                'name' => 'Администратор',
                'phone' => '79000000000',
                'password' => 'admin',
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );

        User::query()
            ->where('email', 'admin@motoshop.local')
            ->where('id', '!=', $admin->id)
            ->delete();
    }
}
