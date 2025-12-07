<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Url;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Url>
 */
class UrlFactory extends Factory
{
    protected $model = Url::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'user_id' => User::factory(),
            'slug' => Str::lower(Str::random(8)),
            'destination' => $this->faker->url(),
        ];
    }
}
