<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        Company::firstOrCreate(['name' => 'Acme Inc']);

        $password = Hash::make('password');

        DB::statement(
            'INSERT OR IGNORE INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, datetime("now"), datetime("now"))',
            [
                'Super Admin',
                'superadmin@example.com',
                $password,
                Role::SUPER_ADMIN,
            ]
        );
    }
}
