<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Company;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        $primaryCompany = Company::firstOrCreate(['name' => 'Acme Inc']);
        $secondaryCompany = Company::firstOrCreate(['name' => 'sembark Labs']);

        $users = [
            [
                'name' => 'Demo Admin',
                'email' => 'admin@example.com',
                'role' => Role::ADMIN,
                'company_id' => $primaryCompany->id,
            ],
            [
                'name' => 'Demo Sales',
                'email' => 'sales@example.com',
                'role' => Role::SALES,
                'company_id' => $primaryCompany->id,
            ],
            [
                'name' => 'Demo Member',
                'email' => 'member@example.com',
                'role' => Role::MEMBER,
                'company_id' => $secondaryCompany->id,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'company_id' => $user['company_id'],
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'password' => Hash::make('password'),
                ]
            );
        }

        $inviter = User::where('email', 'admin@example.com')->first();

        if ($inviter) {
            $invitation = Invitation::firstOrNew(['email' => 'new.member@globexlabs.example']);

            $invitation->fill([
                'invited_by' => $inviter->id,
                'company_id' => $secondaryCompany->id,
                'role' => Role::MEMBER,
                'token' => $invitation->token ?? (string) Str::uuid(),
                'accepted_at' => null,
            ]);

            $invitation->save();
        }
    }
}
