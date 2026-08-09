<?php

namespace App\Models;

use App\Enums\AbsensiStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'user_id',
        'status',
        'schedule_in',
        'schedule_out',
        'checked_in_at',
        'checked_out_at',
        'checked_in_status',
        'checked_out_status',
        'description',
        'wfhwfo',
        'lokasi_user',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'status'             => AbsensiStatus::class,
        'checked_in_status'  => AbsensiStatus::class,
        'checked_out_status' => AbsensiStatus::class,
        'created_at'         => 'datetime',
        'updated_at'         => 'datetime',
        'schedule_in'        => 'datetime',
        'schedule_out'       => 'datetime',
        'checked_in_at'      => 'datetime',
        'checked_out_at'     => 'datetime',
        'latitude'           => 'decimal:8',
        'longitude'          => 'decimal:8',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Total durasi telat & pulang cepat dari sekumpulan absensi.
     *
     * Catatan: checked_in_status / checked_out_status di-cast ke enum
     * AbsensiStatus, jadi perbandingannya harus terhadap case enum. Versi lama
     * membandingkan dengan integer mentah (== 4), yang selalu false sehingga
     * total keterlambatan di dashboard selamanya 00:00:00.
     *
     * @param  Collection<int, self>  $absensi
     * @return array{total_telat: string, total_pulang_cepat: string, total_keterlambatan: string}
     */
    public static function kalkulasiKeterlambatan(Collection $absensi): array
    {
        $detikTelat = 0;
        $detikPulangCepat = 0;

        foreach ($absensi as $a) {
            if ($a->checked_in_status === AbsensiStatus::MasukTelat && $a->checked_in_at && $a->schedule_in) {
                $detikTelat += $a->schedule_in->diffInSeconds($a->checked_in_at, absolute: true);
            }

            if ($a->checked_out_status === AbsensiStatus::PulangCepat && $a->checked_out_at && $a->schedule_out) {
                $detikPulangCepat += $a->checked_out_at->diffInSeconds($a->schedule_out, absolute: true);
            }
        }

        return [
            'total_telat'         => self::formatDurasi($detikTelat),
            'total_pulang_cepat'  => self::formatDurasi($detikPulangCepat),
            'total_keterlambatan' => self::formatDurasi($detikTelat + $detikPulangCepat),
        ];
    }

    /**
     * Data grafik jam masuk/pulang untuk setiap hari dalam satu bulan.
     *
     * @param  Collection<int, self>  $absensi  Absensi dalam bulan yang sama.
     * @return array{x: array<int, string>, y: array<int, int|null>, z: array<int, int|null>}
     */
    public static function chartHarian(Collection $absensi, ?Carbon $bulan = null): array
    {
        $bulan ??= Carbon::now();
        $akhirBulan = $bulan->copy()->endOfMonth();

        $perTanggal = $absensi->keyBy(fn (self $a) => $a->created_at->format('Y-m-d'));

        $x = [];
        $y = [];
        $z = [];

        for ($hari = $bulan->copy()->startOfMonth(); $hari->lte($akhirBulan); $hari->addDay()) {
            $item = $perTanggal->get($hari->format('Y-m-d'));

            $x[] = $hari->format('Y-m-d');
            $y[] = $item?->checked_in_at?->secondsSinceMidnight();
            $z[] = $item?->checked_out_at?->secondsSinceMidnight();
        }

        return ['x' => $x, 'y' => $y, 'z' => $z];
    }

    private static function formatDurasi(int $detik): string
    {
        return sprintf(
            '%02d:%02d:%02d',
            intdiv($detik, 3600),
            intdiv($detik % 3600, 60),
            $detik % 60
        );
    }
}
