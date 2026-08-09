<?php

namespace Tests\Feature;

use App\Enums\AbsensiStatus;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AbsensiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        // Senin 08:30 — sebelum jam masuk 09:00.
        Carbon::setTestNow(Carbon::parse('2026-08-10 08:30:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_absen_pagi_sebelum_jam_masuk_berstatus_tepat_waktu(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/absen/pagi', ['wfhwfo' => 'WFO']);

        $response->assertOk()->assertJson(['success' => true]);

        $absensi = Absensi::where('user_id', $this->user->id)->sole();

        $this->assertSame(AbsensiStatus::TepatWaktu, $absensi->checked_in_status);
        $this->assertSame(AbsensiStatus::BelumAbsen, $absensi->checked_out_status);
        $this->assertSame(AbsensiStatus::Hadir, $absensi->status);
        $this->assertSame('WFO', $absensi->wfhwfo);
    }

    public function test_absen_pagi_setelah_jam_masuk_berstatus_telat(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-08-10 09:15:00'));

        $this->actingAs($this->user)
            ->postJson('/absen/pagi', ['wfhwfo' => 'WFH'])
            ->assertOk();

        $this->assertSame(
            AbsensiStatus::MasukTelat,
            Absensi::where('user_id', $this->user->id)->sole()->checked_in_status
        );
    }

    public function test_absen_pagi_kedua_kali_ditolak(): void
    {
        $this->actingAs($this->user)->postJson('/absen/pagi', ['wfhwfo' => 'WFO'])->assertOk();

        $this->actingAs($this->user)
            ->postJson('/absen/pagi', ['wfhwfo' => 'WFO'])
            ->assertStatus(422)
            ->assertJson(['success' => false]);

        $this->assertSame(1, Absensi::where('user_id', $this->user->id)->count());
    }

    public function test_absen_pagi_wajib_memilih_mode_kerja(): void
    {
        $this->actingAs($this->user)
            ->postJson('/absen/pagi', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors('wfhwfo');
    }

    public function test_absen_sore_tanpa_absen_pagi_ditolak(): void
    {
        $this->actingAs($this->user)
            ->postJson('/absen/sore')
            ->assertStatus(404);
    }

    public function test_absen_sore_sebelum_jam_pulang_berstatus_pulang_cepat(): void
    {
        $this->actingAs($this->user)->postJson('/absen/pagi', ['wfhwfo' => 'WFO'])->assertOk();

        // Jam pulang Senin adalah 15:00.
        Carbon::setTestNow(Carbon::parse('2026-08-10 14:00:00'));

        $this->actingAs($this->user)->postJson('/absen/sore')->assertOk();

        $absensi = Absensi::where('user_id', $this->user->id)->sole();

        $this->assertSame(AbsensiStatus::PulangCepat, $absensi->checked_out_status);
        $this->assertNotNull($absensi->checked_out_at);
    }

    public function test_absen_sore_pada_hari_jumat_memakai_jadwal_jumat(): void
    {
        // Jumat 14:45 — sudah lewat jam pulang biasa (15:00? belum), tapi
        // jadwal Jumat 15:30 membuatnya tetap terhitung pulang cepat.
        Carbon::setTestNow(Carbon::parse('2026-08-14 08:30:00'));
        $this->actingAs($this->user)->postJson('/absen/pagi', ['wfhwfo' => 'WFO'])->assertOk();

        Carbon::setTestNow(Carbon::parse('2026-08-14 15:15:00'));
        $this->actingAs($this->user)->postJson('/absen/sore')->assertOk();

        $absensi = Absensi::where('user_id', $this->user->id)->sole();

        $this->assertSame(AbsensiStatus::PulangCepat, $absensi->checked_out_status);
        $this->assertSame('15:30', $absensi->schedule_out->format('H:i'));
    }

    public function test_absen_sore_kedua_kali_ditolak(): void
    {
        $this->actingAs($this->user)->postJson('/absen/pagi', ['wfhwfo' => 'WFO'])->assertOk();
        $this->actingAs($this->user)->postJson('/absen/sore')->assertOk();

        $this->actingAs($this->user)
            ->postJson('/absen/sore')
            ->assertStatus(422);
    }

    public function test_lokasi_absen_disimpan_dari_koordinat(): void
    {
        Http::fake([
            'api.geoapify.com/*' => Http::response([
                'features' => [
                    ['properties' => ['formatted' => 'Kominfotik Jakarta Barat']],
                ],
            ]),
        ]);

        config(['magang.geoapify.key' => 'test-key']);

        $this->actingAs($this->user)->postJson('/absen/pagi', [
            'wfhwfo'    => 'WFO',
            'latitude'  => -6.168,
            'longitude' => 106.766,
        ])->assertOk()->assertJson(['location' => 'Kominfotik Jakarta Barat']);

        $absensi = Absensi::where('user_id', $this->user->id)->sole();

        $this->assertSame('Kominfotik Jakarta Barat', $absensi->lokasi_user);
        $this->assertEquals(-6.168, (float) $absensi->latitude);
    }

    public function test_absen_tetap_berhasil_saat_geocoding_gagal(): void
    {
        Http::fake(['api.geoapify.com/*' => Http::response([], 500)]);
        config(['magang.geoapify.key' => 'test-key']);

        $this->actingAs($this->user)->postJson('/absen/pagi', [
            'wfhwfo'    => 'WFO',
            'latitude'  => -6.168,
            'longitude' => 106.766,
        ])->assertOk();

        $this->assertSame(
            'Lat: -6.168, Lon: 106.766',
            Absensi::where('user_id', $this->user->id)->sole()->lokasi_user
        );
    }

    public function test_koordinat_nol_tidak_dianggap_lokasi(): void
    {
        $this->actingAs($this->user)->postJson('/absen/pagi', [
            'wfhwfo'    => 'WFO',
            'latitude'  => 0,
            'longitude' => 0,
        ])->assertOk();

        $this->assertNull(Absensi::where('user_id', $this->user->id)->sole()->lokasi_user);
    }

    public function test_absen_sore_tidak_menghapus_lokasi_absen_pagi(): void
    {
        config(['magang.geoapify.key' => null]);

        $this->actingAs($this->user)->postJson('/absen/pagi', [
            'wfhwfo'    => 'WFO',
            'latitude'  => -6.168,
            'longitude' => 106.766,
        ])->assertOk();

        Carbon::setTestNow(Carbon::parse('2026-08-10 15:30:00'));

        // Absen sore tanpa GPS.
        $this->actingAs($this->user)->postJson('/absen/sore')->assertOk();

        $this->assertSame(
            'Lat: -6.168, Lon: 106.766',
            Absensi::where('user_id', $this->user->id)->sole()->lokasi_user
        );
    }

    public function test_tipe_absen_tidak_dikenal_ditolak(): void
    {
        $this->actingAs($this->user)
            ->postJson('/absen/malam', ['wfhwfo' => 'WFO'])
            ->assertStatus(404);
    }

    public function test_tamu_tidak_bisa_absen(): void
    {
        $this->postJson('/absen/pagi', ['wfhwfo' => 'WFO'])->assertStatus(401);
    }
}
