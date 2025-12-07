<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Invitation>
 */
class InvitationFactory extends Factory
{
    protected $model = Invitation::class;

    public function definition(): array
    {
        return [
            'invited_by' => User::factory(),
            'company_id' => Company::factory(),
            'email' => $this->faker->unique()->safeEmail(),
            'role' => 'member',
            'token' => (string) Str::uuid(),
            'accepted_at' => null,
        ];
    }
}
