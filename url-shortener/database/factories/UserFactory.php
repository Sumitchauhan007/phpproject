<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => Role::MEMBER,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function superAdmin(): static
    {
        return $this->state(fn () => [
            'company_id' => null,
            'role' => Role::SUPER_ADMIN,
        ]);
    }

    public function admin(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'company_id' => $attributes['company_id'] ?? Company::factory(),
                'role' => Role::ADMIN,
            ];
        });
    }

    public function member(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'company_id' => $attributes['company_id'] ?? Company::factory(),
                'role' => Role::MEMBER,
            ];
        });
    }

    public function sales(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'company_id' => $attributes['company_id'] ?? Company::factory(),
                'role' => Role::SALES,
            ];
        });
    }

    public function manager(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'company_id' => $attributes['company_id'] ?? Company::factory(),
                'role' => Role::MANAGER,
            ];
        });
    }
}
