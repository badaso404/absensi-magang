<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * Regresi untuk bug lama: checked_in_status di-cast ke enum AbsensiStatus,
 * tapi dibandingkan dengan integer mentah (== 4). Perbandingan itu selalu
 * false, jadi total keterlambatan di dashboard selamanya 00:00:00.
 */
class KeterlambatanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::parse('2026-08-10 16:00:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_menghitung_total_telat(): void
    {
        $user = User::factory()->create();

        Absensi::factory()->for($user)->telat(30)->create();
        Absensi::factory()->for($user)->telat(15)->create();

        $hasil = Absensi::kalkulasiKeterlambatan(Absensi::where('user_id', $user->id)->get());

        $this->assertSame('00:45:00', $hasil['total_telat']);
        $this->assertSame('00:00:00', $hasil['total_pulang_cepat']);
        $this->assertSame('00:45:00', $hasil['total_keterlambatan']);
    }

    public function test_menghitung_total_pulang_cepat(): void
    {
        $user = User::factory()->create();

        Absensi::factory()->for($user)->pulangCepat(20)->create();

        $hasil = Absensi::kalkulasiKeterlambatan(Absensi::where('user_id', $user->id)->get());

        $this->assertSame('00:20:00', $hasil['total_pulang_cepat']);
        $this->assertSame('00:20:00', $hasil['total_keterlambatan']);
    }

    public function test_absensi_tepat_waktu_tidak_menambah_keterlambatan(): void
    {
        $user = User::factory()->create();

        Absensi::factory()->for($user)->count(3)->create();

        $hasil = Absensi::kalkulasiKeterlambatan(Absensi::where('user_id', $user->id)->get());

        $this->assertSame('00:00:00', $hasil['total_keterlambatan']);
    }

    public function test_durasi_lebih_dari_satu_jam_diformat_benar(): void
    {
        $user = User::factory()->create();

        Absensi::factory()->for($user)->telat(95)->create();

        $hasil = Absensi::kalkulasiKeterlambatan(Absensi::where('user_id', $user->id)->get());

        $this->assertSame('01:35:00', $hasil['total_telat']);
    }

    public function test_dashboard_menampilkan_total_keterlambatan(): void
    {
        $user = User::factory()->create();
        Absensi::factory()->for($user)->telat(30)->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $this->assertSame(
            '00:30:00',
            $response->viewData('kalkulasiKeterlambatan')['total_keterlambatan']
        );
    }

    public function test_tombol_absen_hilang_setelah_absen_pagi_dan_sore(): void
    {
        $user = User::factory()->create();
        Absensi::factory()->for($user)->create();

        $response = $this->actingAs($user)->get('/');

        // Versi lama selalu mengembalikan 'sore' karena ada blok "MODE TEST".
        $this->assertSame('selesai', $response->viewData('toggleAbsenPagi'));
    }

    public function test_tahap_absen_sore_saat_belum_absen_pulang(): void
    {
        $user = User::factory()->create();
        Absensi::factory()->for($user)->belumPulang()->create();

        $response = $this->actingAs($user)->get('/');

        $this->assertSame('sore', $response->viewData('toggleAbsenPagi'));
    }

    public function test_tahap_absen_pagi_saat_belum_ada_absensi(): void
    {
        $response = $this->actingAs(User::factory()->create())->get('/');

        $this->assertSame('pagi', $response->viewData('toggleAbsenPagi'));
    }

    public function test_jadwal_dashboard_sama_dengan_yang_tersimpan_di_absensi(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        // Senin: 09:00 - 15:00. Versi lama menampilkan 14:00 di dashboard
        // padahal absensi menyimpan 15:00.
        $this->assertSame('09:00', $response->viewData('schedule_in'));
        $this->assertSame('15:00', $response->viewData('schedule_out'));
    }
}
