<?php

namespace Tests\Feature;

use App\Models\LaporanKegiatan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanKegiatanTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_bisa_menambah_laporan(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/laporan-kegiatan', [
            'tanggal'         => '2026-08-10',
            'detail_kegiatan' => 'Membantu instalasi jaringan',
            'lokasi'          => 'Kominfotik Jakarta Barat',
        ])->assertRedirect(route('laporan-kegiatan.index'));

        $this->assertDatabaseHas('laporan_kegiatan', [
            'user_id'         => $user->id,
            'detail_kegiatan' => 'Membantu instalasi jaringan',
            'bulan'           => 'Agustus 2026',
        ]);
    }

    public function test_laporan_wajib_diisi_lengkap(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/laporan-kegiatan', [])
            ->assertSessionHasErrors(['tanggal', 'detail_kegiatan', 'lokasi']);
    }

    public function test_daftar_laporan_hanya_menampilkan_milik_sendiri(): void
    {
        $user = User::factory()->create();

        $this->buatLaporan($user, 'Kegiatan saya');
        $this->buatLaporan(User::factory()->create(), 'Kegiatan orang lain');

        $response = $this->actingAs($user)->get('/laporan-kegiatan');

        $response->assertOk();
        $this->assertCount(1, $response->viewData('laporan'));
    }

    public function test_filter_bulan_dan_tahun(): void
    {
        $user = User::factory()->create();

        $this->buatLaporan($user, 'Agustus', '2026-08-10');
        $this->buatLaporan($user, 'Juli', '2026-07-10');

        $response = $this->actingAs($user)->get('/laporan-kegiatan?month=8&year=2026');

        $response->assertOk();
        $this->assertCount(1, $response->viewData('laporan'));
        $this->assertSame('Agustus', $response->viewData('laporan')->first()->detail_kegiatan);
    }

    /**
     * Regresi berlapis: kolom users.pekerjaan / bidang_suku_dinas belum ada di
     * database (migrasi pending), DAN route PUT /laporan-kegiatan/setting
     * terdaftar setelah PUT /laporan-kegiatan/{laporanKegiatan} sehingga
     * dicocokkan ke route-model-binding dengan id "setting" lalu 404.
     */
    public function test_user_bisa_menyimpan_pengaturan_laporan(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put('/laporan-kegiatan/setting', [
            'pekerjaan'         => 'Technical Support Keamanan Informasi',
            'bidang_suku_dinas' => 'Aplikasi Siber dan Statistik',
        ])->assertRedirect(route('laporan-kegiatan.setting'));

        $user->refresh();

        $this->assertSame('Technical Support Keamanan Informasi', $user->pekerjaan);
        $this->assertSame('Aplikasi Siber dan Statistik', $user->bidang_suku_dinas);
    }

    public function test_halaman_pengaturan_laporan_bisa_dibuka(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/laporan-kegiatan/setting')
            ->assertOk();
    }

    public function test_user_bisa_mengubah_laporan_sendiri(): void
    {
        $user = User::factory()->create();
        $laporan = $this->buatLaporan($user, 'Draft awal');

        $this->actingAs($user)->put("/laporan-kegiatan/{$laporan->id}", [
            'tanggal'         => '2026-08-11',
            'detail_kegiatan' => 'Versi final',
            'lokasi'          => 'Kantor',
        ])->assertRedirect(route('laporan-kegiatan.index'));

        $this->assertSame('Versi final', $laporan->fresh()->detail_kegiatan);
    }

    public function test_user_bisa_menghapus_laporan_sendiri(): void
    {
        $user = User::factory()->create();
        $laporan = $this->buatLaporan($user, 'Akan dihapus');

        $this->actingAs($user)
            ->delete("/laporan-kegiatan/{$laporan->id}")
            ->assertRedirect(route('laporan-kegiatan.index'));

        $this->assertDatabaseMissing('laporan_kegiatan', ['id' => $laporan->id]);
    }

    private function buatLaporan(User $user, string $detail, string $tanggal = '2026-08-10'): LaporanKegiatan
    {
        return LaporanKegiatan::create([
            'user_id'         => $user->id,
            'tanggal'         => $tanggal,
            'detail_kegiatan' => $detail,
            'lokasi'          => 'Kantor',
            'bulan'           => now()->parse($tanggal)->isoFormat('MMMM YYYY'),
        ]);
    }
}
