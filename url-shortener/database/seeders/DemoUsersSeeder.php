<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::firstOrCreate(['name' => 'Acme Inc']);

        $users = [
            [
                'name' => 'Demo Admin',
                'email' => 'admin@example.com',
                'role' => Role::ADMIN,
            ],
            [
                'name' => 'Demo Sales',
                'email' => 'sales@example.com',
                'role' => Role::SALES,
            ],
            [
                'name' => 'Demo Member',
                'email' => 'member@example.com',
                'role' => Role::MEMBER,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'company_id' => $company->id,
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'password' => Hash::make('password'),
                ]
            );
        }
    }
}
