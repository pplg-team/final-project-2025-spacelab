<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Role;

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
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password_hash' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => function () {
                $role = Role::first();

                if (! $role) {
                    $role = Role::create([
                        'name' => 'Siswa',
                    ]);
                }

                return $role->id;
            },
        ];
    }

    public function asStudent(): static
    {
        return $this->state(function () {
            $role = \App\Models\Role::firstOrCreate(
                ['name' => 'Siswa'],
                []
            );

            return ['role_id' => $role->id];
        });
    }

    public function asTeacher(): static
    {
        return $this->state(function () {
            $role = \App\Models\Role::firstOrCreate(
                ['name' => 'Guru'],
                []
            );

            return ['role_id' => $role->id];
        });
    }

    public function asAdmin(): static
    {
        return $this->state(function () {
            $role = \App\Models\Role::firstOrCreate(
                ['name' => 'Admin'],
                []
            );

            return ['role_id' => $role->id];
        });
    }

    public function asStaff(): static
    {
        return $this->state(function () {
            $role = \App\Models\Role::firstOrCreate(
                ['name' => 'Staff'],
                []
            );

            return ['role_id' => $role->id];
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
