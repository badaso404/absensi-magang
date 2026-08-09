<?php

namespace Database\Factories;

use App\Enums\AbsensiStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Absensi>
 */
class AbsensiFactory extends Factory
{
    protected $model = \App\Models\Absensi::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $hari = now();

        return [
            'user_id'            => User::factory(),
            'status'             => AbsensiStatus::Hadir,
            'schedule_in'        => $hari->copy()->setTime(9, 0),
            'schedule_out'       => $hari->copy()->setTime(15, 0),
            'checked_in_at'      => $hari->copy()->setTime(9, 0),
            'checked_out_at'     => $hari->copy()->setTime(15, 0),
            'checked_in_status'  => AbsensiStatus::TepatWaktu,
            'checked_out_status' => AbsensiStatus::TepatWaktu,
            'wfhwfo'             => 'WFO',
        ];
    }

    /**
     * Masuk telat sekian menit dari jadwal.
     */
    public function telat(int $menit = 30): static
    {
        return $this->state(fn (array $attributes) => [
            'checked_in_at'     => $attributes['schedule_in']->copy()->addMinutes($menit),
            'checked_in_status' => AbsensiStatus::MasukTelat,
        ]);
    }

    /**
     * Pulang lebih cepat sekian menit dari jadwal.
     */
    public function pulangCepat(int $menit = 30): static
    {
        return $this->state(fn (array $attributes) => [
            'checked_out_at'     => $attributes['schedule_out']->copy()->subMinutes($menit),
            'checked_out_status' => AbsensiStatus::PulangCepat,
        ]);
    }

    /**
     * Sudah absen pagi tapi belum absen pulang.
     */
    public function belumPulang(): static
    {
        return $this->state(fn () => [
            'checked_out_at'     => null,
            'checked_out_status' => AbsensiStatus::BelumAbsen,
        ]);
    }
}
