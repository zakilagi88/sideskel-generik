<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = User::class;

    public function definition()
    {
        $rwIndex = $this->faker->numberBetween(1, 2); // Pilih acak RW 1 atau 2
        $rtIndex = $this->faker->numberBetween(1, 36); // Pilih acak RT 1 - 36

        $email = '';

        if ($this->faker->boolean) {
            // Pengguna RW
            $email = "RW" . str_pad($rwIndex, 3, '0', STR_PAD_LEFT) . "@kuripan.id";
        } else {
            // Pengguna RT
            $email = "RT" . str_pad($rwIndex, 3, '0', STR_PAD_LEFT) . "_" . str_pad($rtIndex, 3, '0', STR_PAD_LEFT) . "@kuripan.id";
        }

        return [
            'name' => $this->faker->name(),
            'email' => $email,
            'email_verified_at' => now(),
            'password' => bcrypt('kuripan'),
            'remember_token' => Str::random(10),
        ];
    }
}
