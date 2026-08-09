<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Enums\UserSeksi;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'                 => fake()->name(),
            'email'                => fake()->unique()->safeEmail(),
            'identity_number'      => fake()->unique()->numerify('##########'),
            'password'             => static::$password ??= Hash::make('password'),
            'role_id'              => Role::Magang->value,
            'seksi'                => fake()->randomElement(UserSeksi::cases())->value,
            'asal'                 => fake()->company(),
            'jurusan'              => fake()->word(),
            'jenis_kelamin'        => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'tanggal_awal_magang'  => now()->subMonth(),
            'tanggal_akhir_magang' => now()->addMonth(),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role_id' => Role::Admin->value]);
    }

    /**
     * Magang yang periodenya sudah lewat.
     */
    public function selesai(): static
    {
        return $this->state(fn () => ['tanggal_akhir_magang' => now()->subDay()]);
    }
}
