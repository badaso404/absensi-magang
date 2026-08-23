<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\LaporanKegiatan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AksesKontrolTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Regresi IDOR: sebelumnya /rekapabsen/{user} hanya dijaga middleware
     * Authenticate, sehingga user magang bisa membaca rekap absensi siapa pun
     * cukup dengan mengganti angka di URL.
     */
    public function test_magang_tidak_bisa_melihat_rekap_absen_user_lain(): void
    {
        $korban = User::factory()->create();
        Absensi::factory()->for($korban)->create();

        $this->actingAs(User::factory()->create())
            ->get("/rekapabsen/{$korban->id}")
            ->assertRedirect(route('home'));
    }

    public function test_magang_tidak_bisa_export_rekap_absen_user_lain(): void
    {
        $korban = User::factory()->create();

        $this->actingAs(User::factory()->create())
            ->get("/rekapabsen/{$korban->id}/export")
            ->assertRedirect(route('home'));
    }

    public function test_admin_bisa_melihat_rekap_absen_user_lain(): void
    {
        $magang = User::factory()->create();
        Absensi::factory()->for($magang)->create();

        $response = $this->actingAs(User::factory()->admin()->create())
            ->get("/rekapabsen/{$magang->id}");

        $response->assertOk();
        $this->assertCount(1, $response->viewData('rekap'));
        $this->assertTrue($magang->is($response->viewData('rekapUser')));
    }

    public function test_user_selalu_melihat_rekap_sendiri_di_route_tanpa_parameter(): void
    {
        $user = User::factory()->create();
        Absensi::factory()->for($user)->create();
        Absensi::factory()->for(User::factory()->create())->create();

        $response = $this->actingAs($user)->get('/rekapabsen');

        $response->assertOk();
        $this->assertCount(1, $response->viewData('rekap'));
        $this->assertNull($response->viewData('rekapUser'));
    }

    public function test_magang_tidak_bisa_membuka_manajemen_user(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin/user')
            ->assertRedirect(route('home'));
    }

    public function test_magang_tidak_bisa_membuka_laporan_seluruh_magang(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin/laporan-magangs')
            ->assertRedirect(route('home'));
    }

    public function test_admin_bisa_membuka_form_edit_user(): void
    {
        $magang = User::factory()->create();

        // Route ini dulu selalu 500 karena AdminUserController::edit() tidak ada.
        $response = $this->actingAs(User::factory()->admin()->create())
            ->get("/admin/user/{$magang->id}/edit");

        $response->assertOk();
        $this->assertTrue($magang->is($response->viewData('user')));
    }

    public function test_user_tidak_bisa_mengubah_laporan_milik_orang_lain(): void
    {
        $laporan = LaporanKegiatan::create([
            'user_id'         => User::factory()->create()->id,
            'tanggal'         => now()->toDateString(),
            'detail_kegiatan' => 'Rapat internal',
            'lokasi'          => 'Kantor',
            'bulan'           => now()->isoFormat('MMMM YYYY'),
        ]);

        $this->actingAs(User::factory()->create())
            ->get("/laporan-kegiatan/{$laporan->id}/edit")
            ->assertForbidden();
    }

    public function test_user_tidak_bisa_menghapus_laporan_milik_orang_lain(): void
    {
        $laporan = LaporanKegiatan::create([
            'user_id'         => User::factory()->create()->id,
            'tanggal'         => now()->toDateString(),
            'detail_kegiatan' => 'Rapat internal',
            'lokasi'          => 'Kantor',
            'bulan'           => now()->isoFormat('MMMM YYYY'),
        ]);

        $this->actingAs(User::factory()->create())
            ->delete("/laporan-kegiatan/{$laporan->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('laporan_kegiatan', ['id' => $laporan->id]);
    }

    /**
     * Halaman ini kini papan pantau absensi harian: satu baris per magang
     * aktif berikut status absennya, bukan lagi salinan halaman manajemen user
     * (yang dulu mengirim $users/$totalUsers dan tidak memakai data absensi).
     */
    public function test_halaman_absensi_admin_bisa_dibuka(): void
    {
        User::factory()->count(3)->create();

        $response = $this->actingAs(User::factory()->admin()->create())
            ->get('/admin/absensi');

        $response->assertOk();
        $this->assertCount(3, $response->viewData('baris'));
        $this->assertSame(3, $response->viewData('totalMagang'));
    }

    public function test_magang_tidak_bisa_membuka_absensi_admin(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin/absensi')
            ->assertRedirect(route('home'));
    }

    public function test_tamu_diarahkan_ke_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
        $this->get('/absensi')->assertRedirect(route('login'));
        $this->get('/rekapabsen')->assertRedirect(route('login'));
    }
}
