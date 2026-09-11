<?php

namespace Tests\Feature;

use App\Models\LaporanKegiatan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

/**
 * Regresi untuk temuan pentesting. Setiap test di sini pernah gagal sebelum
 * perbaikan yang menyertainya.
 */
class KerentananTest extends TestCase
{
    use RefreshDatabase;

    // --- Masa magang berakhir ---

    public function test_magang_kedaluwarsa_tidak_bisa_login(): void
    {
        User::factory()->create([
            'email'                => 'expired@test.id',
            'password'             => 'rahasia123',
            'tanggal_akhir_magang' => now()->subMonth(),
        ]);

        $this->postJson('/login-attempt', [
            'email'    => 'expired@test.id',
            'password' => 'rahasia123',
            'captcha'  => 'x',
        ])->assertStatus(403);

        $this->assertGuest();
    }

    public function test_sesi_magang_kedaluwarsa_diputus_di_tengah_jalan(): void
    {
        // Sesi dibuat saat masih aktif, lalu masa magang lewat.
        $user = User::factory()->create(['tanggal_akhir_magang' => now()->addDay()]);
        $this->actingAs($user)->get('/')->assertOk();

        $user->update(['tanggal_akhir_magang' => now()->subDay()]);

        $this->get('/')->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_magang_masih_aktif_tetap_bisa_login(): void
    {
        User::factory()->create([
            'email'                => 'aktif@test.id',
            'password'             => 'rahasia123',
            'tanggal_akhir_magang' => now()->addMonth(),
        ]);

        $this->postJson('/login-attempt', [
            'email'    => 'aktif@test.id',
            'password' => 'rahasia123',
            'captcha'  => 'x',
        ])->assertOk();

        $this->assertAuthenticated();
    }

    public function test_admin_tanpa_tanggal_akhir_tidak_ikut_diblokir(): void
    {
        $admin = User::factory()->admin()->create(['tanggal_akhir_magang' => now()->subYear()]);

        $this->actingAs($admin)->get('/admin/user')->assertOk();
    }

    // --- Dokumentasi laporan ---

    public function test_dokumentasi_tersimpan_saat_membuat_laporan(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/laporan-kegiatan', [
            'tanggal'         => '2026-08-01',
            'detail_kegiatan' => 'Membantu input data',
            'lokasi'          => 'Kantor',
            'dokumentasi'     => UploadedFile::fake()->image('bukti.jpg'),
        ])->assertRedirect(route('laporan-kegiatan.index'));

        $laporan = LaporanKegiatan::firstOrFail();

        $this->assertNotNull($laporan->dokumentasi, 'File dokumentasi tidak ikut tersimpan.');
        $this->assertFileExists(public_path('storage/' . $laporan->dokumentasi));

        File::delete(public_path('storage/' . $laporan->dokumentasi));
    }

    public function test_dokumentasi_non_gambar_ditolak(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/laporan-kegiatan', [
            'tanggal'         => '2026-08-01',
            'detail_kegiatan' => 'x',
            'lokasi'          => 'y',
            'dokumentasi'     => UploadedFile::fake()->create('shell.php', 10, 'application/x-php'),
        ])->assertSessionHasErrors('dokumentasi');

        $this->assertSame(0, LaporanKegiatan::count());
    }

    // --- Penghapusan admin ---

    public function test_admin_tidak_bisa_menghapus_akun_sendiri(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->delete("/admin/user/{$admin->id}");

        $this->assertNotNull(User::find($admin->id), 'Admin berhasil menghapus dirinya sendiri.');
    }

    public function test_admin_terakhir_tidak_bisa_dihapus(): void
    {
        $admin = User::factory()->admin()->create();
        $adminLain = User::factory()->admin()->create();

        // Menghapus salah satu masih boleh selagi tersisa lebih dari satu.
        $this->actingAs($admin)->delete("/admin/user/{$adminLain->id}");
        $this->assertNull(User::find($adminLain->id));

        // Yang terakhir tidak boleh hilang, kalau tidak panel terkunci permanen.
        $penerus = User::factory()->admin()->create();
        $this->actingAs($penerus)->delete("/admin/user/{$admin->id}");
        $this->assertNull(User::find($admin->id));

        $this->assertSame(1, User::where('role_id', 1)->count());
    }

    public function test_admin_tetap_bisa_menghapus_magang(): void
    {
        $admin = User::factory()->admin()->create();
        $magang = User::factory()->create();

        $this->actingAs($admin)->delete("/admin/user/{$magang->id}");

        $this->assertNull(User::find($magang->id));
    }

    // --- Filter periode tidak valid ---

    /**
     * @dataProvider urlFilterTidakValid
     */
    public function test_filter_tidak_valid_tidak_membuat_error_500(string $url, bool $admin): void
    {
        $user = $admin
            ? User::factory()->admin()->create()
            : User::factory()->create();

        $this->actingAs($user)->get($url)->assertSuccessful();
    }

    public static function urlFilterTidakValid(): array
    {
        return [
            'laporan year array'  => ['/laporan-kegiatan?year[]=2026', false],
            'laporan month array' => ['/laporan-kegiatan?month[]=1', false],
            'laporan month teks'  => ['/laporan-kegiatan?month=abc', false],
            'laporan month besar' => ['/laporan-kegiatan?month=99', false],
            'rekap year array'    => ['/rekapabsen?year[]=2026', false],
            'rekap month array'   => ['/rekapabsen?month[]=1', false],
            'export laporan'      => ['/laporan-kegiatan/export?year[]=2026', false],
            'admin tanggal teks'  => ['/admin/absensi?tanggal=xyz', true],
            'admin tanggal array' => ['/admin/absensi?tanggal[]=2026-01-01', true],
            'admin laporan year'  => ['/admin/laporan-magangs?year[]=2026', true],
        ];
    }

    public function test_filter_periode_valid_tetap_bekerja(): void
    {
        $user = User::factory()->create();

        LaporanKegiatan::create([
            'user_id' => $user->id, 'tanggal' => '2026-03-10',
            'detail_kegiatan' => 'maret', 'lokasi' => 'kantor', 'bulan' => 'Maret 2026',
        ]);
        LaporanKegiatan::create([
            'user_id' => $user->id, 'tanggal' => '2026-04-10',
            'detail_kegiatan' => 'april', 'lokasi' => 'kantor', 'bulan' => 'April 2026',
        ]);

        $response = $this->actingAs($user)->get('/laporan-kegiatan?month=3&year=2026');

        $response->assertOk();
        $this->assertCount(1, $response->viewData('laporan'));
        $this->assertSame(3, $response->viewData('selectedMonth'));
    }

    // --- Remember me setelah ganti password ---

    public function test_ganti_password_memutar_remember_token(): void
    {
        $user = User::factory()->create(['password' => 'lama123456']);
        $this->actingAs($user)->get('/');
        $tokenLama = $user->fresh()->remember_token;

        $this->actingAs($user)->post('/profil/update-password', [
            'current_password'      => 'lama123456',
            'password'              => 'baru123456',
            'password_confirmation' => 'baru123456',
        ])->assertOk();

        $this->assertNotSame(
            $tokenLama,
            $user->fresh()->remember_token,
            'Cookie remember me lama masih sah setelah password diganti.'
        );
    }

    // --- Koordinat GPS wajib ---

    /**
     * Dialog konfirmasi di browser gampang dilewati lewat DevTools atau curl,
     * jadi penolakan yang sesungguhnya harus terjadi di server.
     *
     * @dataProvider payloadTanpaLokasi
     */
    public function test_absen_tanpa_lokasi_ditolak_server(array $payload): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/absen/pagi', $payload + ['wfhwfo' => 'WFO'])
            ->assertStatus(422);

        $this->assertSame(0, \App\Models\Absensi::count(), 'Absensi tanpa lokasi tetap tercatat.');
    }

    public static function payloadTanpaLokasi(): array
    {
        return [
            'tanpa koordinat'   => [[]],
            'koordinat kosong'  => [['latitude' => null, 'longitude' => null]],
            'null island'       => [['latitude' => 0, 'longitude' => 0]],
            'hanya latitude'    => [['latitude' => -6.175]],
            'bukan angka'       => [['latitude' => 'abc', 'longitude' => 'def']],
            'di luar rentang'   => [['latitude' => 999, 'longitude' => 999]],
        ];
    }

    public function test_absen_sore_juga_wajib_lokasi(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->postJson('/absen/pagi', [
            'wfhwfo' => 'WFO', 'latitude' => -6.175, 'longitude' => 106.827,
        ])->assertOk();

        $this->actingAs($user)->postJson('/absen/sore', [])->assertStatus(422);

        $this->assertNull(\App\Models\Absensi::firstOrFail()->checked_out_at);
    }

    public function test_koordinat_valid_tetap_tersimpan(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->postJson('/absen/pagi', [
            'wfhwfo'    => 'WFO',
            'latitude'  => -6.175392,
            'longitude' => 106.827153,
        ])->assertOk();

        $absensi = \App\Models\Absensi::firstOrFail();

        $this->assertEqualsWithDelta(-6.175392, (float) $absensi->latitude, 0.000001);
        $this->assertEqualsWithDelta(106.827153, (float) $absensi->longitude, 0.000001);
    }

    // --- Modal Detail Absensi ---

    /**
     * Modal detail membaca atribut data-* dari <tr>. Sebelumnya JS membacanya
     * dari tombol (yang tidak punya atribut itu), dan kolom "note" yang dirujuk
     * Blade juga tidak ada di tabel — modal selalu menampilkan "-" semua.
     */
    public function test_baris_absensi_membawa_data_untuk_modal_detail(): void
    {
        $user = User::factory()->create();

        \App\Models\Absensi::factory()->for($user)->create([
            'checked_in_at'  => now()->setTime(16, 30, 26),
            'checked_out_at' => now()->setTime(17, 5, 10),
            'lokasi_user'    => 'Kominfotik Jakarta Barat',
            'description'    => 'Lembur input data',
        ]);

        $html = $this->actingAs($user)->get('/absensi')->assertOk()->getContent();

        $this->assertStringContainsString('data-note="Lembur input data"', $html);
        $this->assertStringContainsString('data-lokasi="Kominfotik Jakarta Barat"', $html);
        $this->assertStringContainsString('data-checked-in="16:30:26"', $html);
        $this->assertStringContainsString('data-checked-out="17:05:10"', $html);
    }

    public function test_absensi_belum_pulang_tidak_menampilkan_jam_pulang_palsu(): void
    {
        $user = User::factory()->create();

        \App\Models\Absensi::factory()->for($user)->create([
            'checked_in_at'  => now()->setTime(9, 0, 0),
            'checked_out_at' => null,
        ]);

        $html = $this->actingAs($user)->get('/absensi')->assertOk()->getContent();

        $this->assertStringContainsString('data-checked-out=""', $html);
    }

    // --- Kelengkapan form edit profil ---

    public function test_field_profil_tambahan_tersimpan(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/profil/update', [
            'name'          => 'Test User',
            'asal'          => 'Universitas X',
            'jurusan'       => 'Informatika',
            'jenis_kelamin' => 'Laki-laki',
            'alamat'        => 'Jl. Mawar 1',
            'tanggal_lahir' => '2003-05-17',
            'no_telp'       => '081234567890',
            'instagram'     => 'https://instagram.com/tes',
            'linkedin'      => 'https://linkedin.com/in/tes',
        ])->assertOk();

        $user->refresh();

        $this->assertSame('081234567890', $user->no_telp);
        $this->assertSame('https://instagram.com/tes', $user->instagram);
        $this->assertSame('https://linkedin.com/in/tes', $user->linkedin);
        $this->assertSame('2003-05-17', \Carbon\Carbon::parse($user->tanggal_lahir)->format('Y-m-d'));
    }

    public function test_form_edit_profil_menampilkan_field_baru(): void
    {
        $html = $this->actingAs(User::factory()->create())
            ->get('/profil/edit')->assertOk()->getContent();

        foreach (['name="no_telp"', 'name="instagram"', 'name="linkedin"', 'name="tanggal_lahir"'] as $field) {
            $this->assertStringContainsString($field, $html, "Field {$field} tidak ada di form.");
        }
    }

    /**
     * tanggal_akhir_magang menentukan apakah akun masih boleh login, jadi
     * magang tidak boleh bisa memperpanjang masa magangnya sendiri.
     */
    public function test_magang_tidak_bisa_mengubah_periode_magangnya_sendiri(): void
    {
        $akhir = now()->addDays(3)->startOfDay();
        $user = User::factory()->create([
            'tanggal_awal_magang'  => now()->subMonth()->startOfDay(),
            'tanggal_akhir_magang' => $akhir,
        ]);

        $this->actingAs($user)->post('/profil/update', [
            'name'                 => 'Test User',
            'asal'                 => 'Universitas X',
            'jurusan'              => 'Informatika',
            'jenis_kelamin'        => 'Laki-laki',
            'alamat'               => 'Jl. Mawar 1',
            'tanggal_awal_magang'  => '2020-01-01',
            'tanggal_akhir_magang' => now()->addYears(5)->format('Y-m-d'),
        ])->assertOk();

        $this->assertSame(
            $akhir->format('Y-m-d'),
            \Carbon\Carbon::parse($user->fresh()->tanggal_akhir_magang)->format('Y-m-d'),
            'Magang berhasil memperpanjang masa magangnya sendiri.'
        );
    }

    public function test_no_telp_harus_angka(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->postJson('/profil/update', [
            'name'          => 'Test User',
            'asal'          => 'Universitas X',
            'jurusan'       => 'Informatika',
            'jenis_kelamin' => 'Laki-laki',
            'alamat'        => 'Jl. Mawar 1',
            'no_telp'       => '<script>alert(1)</script>',
        ])->assertStatus(422)->assertJsonValidationErrors('no_telp');
    }

    // --- Dropdown profil di header ---

    /**
     * Menu Profil & Pengaturan di dropdown avatar masih href="#" bawaan
     * template, jadi tidak bisa diklik ke mana pun. Nama user juga hardcode
     * "Hi, Heather!" dari template aslinya.
     */
    public function test_dropdown_header_menautkan_ke_halaman_asli(): void
    {
        $user = User::factory()->create(['name' => 'Bagas Pratama']);

        $html = $this->actingAs($user)->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('href="' . route('profil') . '"', $html);
        $this->assertStringContainsString('href="' . route('profil-edit') . '"', $html);
        $this->assertStringNotContainsString('Heather', $html);
        $this->assertStringContainsString('Hai, Bagas!', $html);
    }

    public function test_halaman_tujuan_dropdown_bisa_dibuka(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('profil'))->assertOk();
        $this->actingAs($user)->get(route('profil-edit'))->assertOk();
    }

    // --- Admin monitor-only ---

    public function test_admin_tidak_bisa_membuka_halaman_absensi(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get('/absensi')
            ->assertRedirect(route('home'));
    }

    public function test_admin_tidak_bisa_mengirim_absen(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->postJson('/absen/pagi', [
            'wfhwfo' => 'WFO', 'latitude' => -6.175, 'longitude' => 106.827,
        ])->assertStatus(403);

        $this->assertSame(0, \App\Models\Absensi::where('user_id', $admin->id)->count());
    }

    public function test_magang_tetap_bisa_absen(): void
    {
        $this->actingAs(User::factory()->create())->get('/absensi')->assertOk();
    }

    public function test_menu_absensi_disembunyikan_dari_admin(): void
    {
        $htmlAdmin = $this->actingAs(User::factory()->admin()->create())
            ->get('/')->assertOk()->getContent();

        $this->assertStringNotContainsString('href="' . route('absensi') . '"', $htmlAdmin);
        $this->assertStringContainsString('href="' . route('admin-absensi') . '"', $htmlAdmin);

        $htmlMagang = $this->actingAs(User::factory()->create())
            ->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('href="' . route('absensi') . '"', $htmlMagang);
    }

    public function test_beranda_admin_menampilkan_ringkasan_pemantauan(): void
    {
        // Dipatok ke hari kerja: di akhir pekan tidak ada yang dihitung bolos,
        // jadi tanpa ini test ikut gagal setiap Sabtu-Minggu.
        \Illuminate\Support\Carbon::setTestNow('2026-08-24 10:00:00'); // Senin

        $admin = User::factory()->admin()->create();

        $hadir = User::factory()->create(['name' => 'Magang Hadir']);
        $absen = User::factory()->create(['name' => 'Magang Absen']);
        User::factory()->create([
            'name' => 'Magang Lulus',
            'tanggal_akhir_magang' => now()->subMonth(),
        ]);

        \App\Models\Absensi::factory()->for($hadir)->create([
            'checked_in_at' => now()->setTime(9, 0),
            'checked_in_status' => \App\Enums\AbsensiStatus::MasukTelat,
        ]);

        $response = $this->actingAs($admin)->get('/')->assertOk();

        // Magang yang sudah lulus tidak ikut dihitung.
        $this->assertSame(2, $response->viewData('totalMagang'));
        $this->assertSame(1, $response->viewData('sudahAbsen'));
        $this->assertSame(1, $response->viewData('belumAbsen'));
        $this->assertSame(1, $response->viewData('telat'));

        $html = $response->getContent();
        $this->assertStringContainsString('Magang Hadir', $html);
        $this->assertStringContainsString('Magang Absen', $html);
        $this->assertStringNotContainsString('Magang Lulus', $html);

        \Illuminate\Support\Carbon::setTestNow();
    }

    public function test_beranda_magang_tetap_dashboard_pribadi(): void
    {
        $response = $this->actingAs(User::factory()->create())->get('/')->assertOk();

        $response->assertViewIs('beranda.index');
        $this->assertNotNull($response->viewData('kalkulasiKeterlambatan'));
    }

    // --- Admin: laporan monitor-only + filter unit ---

    /**
     * @dataProvider ruteLaporanPeserta
     */
    public function test_admin_tidak_bisa_akses_laporan_kegiatan_pribadi(string $metode, string $url): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->call($metode, $url)
            ->assertRedirect(route('home'));
    }

    public static function ruteLaporanPeserta(): array
    {
        return [
            'index'   => ['GET', '/laporan-kegiatan'],
            'create'  => ['GET', '/laporan-kegiatan/create'],
            'store'   => ['POST', '/laporan-kegiatan'],
            'setting' => ['GET', '/laporan-kegiatan/setting'],
            'export'  => ['GET', '/laporan-kegiatan/export'],
        ];
    }

    public function test_magang_tetap_bisa_akses_laporan_kegiatan(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/laporan-kegiatan')->assertOk();
    }

    public function test_menu_laporan_kegiatan_disembunyikan_dari_admin(): void
    {
        $html = $this->actingAs(User::factory()->admin()->create())
            ->get('/')->assertOk()->getContent();

        $this->assertStringNotContainsString('href="' . route('laporan-kegiatan.index') . '"', $html);
    }

    public function test_filter_unit_pada_laporan_magang(): void
    {
        $admin = User::factory()->admin()->create();

        $astik = User::factory()->create(['name' => 'Anak ASTIK', 'seksi' => \App\Enums\UserSeksi::ASTIK]);
        $id    = User::factory()->create(['name' => 'Anak ID', 'seksi' => \App\Enums\UserSeksi::ID]);

        foreach ([$astik, $id] as $u) {
            LaporanKegiatan::create([
                'user_id' => $u->id, 'tanggal' => '2026-08-01',
                'detail_kegiatan' => 'x', 'lokasi' => 'y', 'bulan' => 'Agustus 2026',
            ]);
        }

        // Tanpa filter: dua-duanya tampil.
        $semua = $this->actingAs($admin)->get('/admin/laporan-magangs')->assertOk();
        $this->assertSame(2, $semua->viewData('totalMagang'));
        $this->assertSame(2, $semua->viewData('totalLaporan'));

        // Filter ASTIK: hanya satu, dan totalnya ikut menyesuaikan.
        $filtered = $this->actingAs($admin)
            ->get('/admin/laporan-magangs?seksi=' . \App\Enums\UserSeksi::ASTIK->value)
            ->assertOk();

        $this->assertSame(1, $filtered->viewData('totalMagang'));
        $this->assertSame(1, $filtered->viewData('totalLaporan'));

        $html = $filtered->getContent();
        $this->assertStringContainsString('Anak ASTIK', $html);
        $this->assertStringNotContainsString('Anak ID', $html);
    }

    public function test_filter_unit_pada_absensi_admin(): void
    {
        $admin = User::factory()->admin()->create();

        $astik = User::factory()->create(['name' => 'Absen ASTIK', 'seksi' => \App\Enums\UserSeksi::ASTIK]);
        $kip   = User::factory()->create(['name' => 'Absen KIP', 'seksi' => \App\Enums\UserSeksi::KIP]);

        \App\Models\Absensi::factory()->for($astik)->create();
        \App\Models\Absensi::factory()->for($kip)->create();

        $semua = $this->actingAs($admin)->get('/admin/absensi')->assertOk();
        $this->assertCount(2, $semua->viewData('baris'));

        $filtered = $this->actingAs($admin)
            ->get('/admin/absensi?seksi=' . \App\Enums\UserSeksi::ASTIK->value)
            ->assertOk();

        $baris = $filtered->viewData('baris');
        $this->assertCount(1, $baris, 'Tabel absensi tidak ikut tersaring per unit.');
        $this->assertSame('Absen ASTIK', $baris->first()['user']->name);
    }

    public function test_filter_unit_tidak_valid_diabaikan(): void
    {
        $admin = User::factory()->admin()->create();

        foreach (['?seksi=99', '?seksi=abc', '?seksi[]=1', '?seksi='] as $q) {
            $this->actingAs($admin)->get('/admin/laporan-magangs' . $q)->assertOk();
            $this->actingAs($admin)->get('/admin/absensi' . $q)->assertOk();
        }
    }

    public function test_form_filter_absensi_tidak_melempar_ke_halaman_user(): void
    {
        $html = $this->actingAs(User::factory()->admin()->create())
            ->get('/admin/absensi')->assertOk()->getContent();

        $this->assertStringContainsString('action="' . route('admin-absensi') . '"', $html);
    }

    // --- Form edit user admin ---

    /**
     * Dropdown seksi tidak pernah terpilih karena $user->seksi adalah objek
     * enum sementara option-nya angka. Karena seksi wajib diisi, admin yang
     * mengedit apa pun selalu ditolak "seksi wajib diisi" dan harus memilih
     * ulang unitnya setiap kali.
     */
    public function test_form_edit_user_memilih_seksi_yang_tersimpan(): void
    {
        $admin = User::factory()->admin()->create();
        $magang = User::factory()->create(['seksi' => \App\Enums\UserSeksi::KIP]);

        $html = $this->actingAs($admin)
            ->get("/admin/user/{$magang->id}/edit")->assertOk()->getContent();

        $this->assertStringContainsString(
            'value="' . \App\Enums\UserSeksi::KIP->value . '" selected',
            $html,
            'Seksi yang tersimpan tidak terpilih di dropdown.'
        );
    }

    public function test_form_edit_user_terisi_data_lama(): void
    {
        $admin = User::factory()->admin()->create();
        $magang = User::factory()->create([
            'name'            => 'Budi Santoso',
            'email'           => 'budi@test.id',
            'identity_number' => '12345',
            'asal'            => 'Universitas X',
            'jurusan'         => 'Informatika',
            'no_telp'         => '081234567890',
        ]);

        $html = $this->actingAs($admin)
            ->get("/admin/user/{$magang->id}/edit")->assertOk()->getContent();

        foreach (['Budi Santoso', 'budi@test.id', '12345', 'Universitas X', 'Informatika', '081234567890'] as $nilai) {
            $this->assertStringContainsString('value="' . $nilai . '"', $html);
        }
    }

    /**
     * Admin hanya mengubah satu field; sisanya dikirim apa adanya dari form
     * yang sudah terisi. Ini harus berhasil tanpa mengisi ulang apa pun.
     */
    public function test_admin_bisa_update_satu_field_saja(): void
    {
        $admin = User::factory()->admin()->create();
        $magang = User::factory()->create([
            'name'            => 'Budi',
            'email'           => 'budi@test.id',
            'identity_number' => '12345',
            'seksi'           => \App\Enums\UserSeksi::ASTIK,
        ]);

        $this->actingAs($admin)->put("/admin/user/{$magang->id}", [
            'name'            => 'Budi Santoso',
            'email'           => 'budi@test.id',
            'identity_number' => '12345',
            'seksi'           => \App\Enums\UserSeksi::ASTIK->value,
        ])->assertSessionHasNoErrors();

        $magang->refresh();

        $this->assertSame('Budi Santoso', $magang->name);
        $this->assertSame(\App\Enums\UserSeksi::ASTIK, $magang->seksi, 'Seksi berubah padahal tidak diubah.');
    }

    // --- Pesan validasi terbaca ---

    public function test_pesan_validasi_tidak_menampilkan_kunci_mentah(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->put("/admin/user/{$admin->id}", []);

        $pesan = session('errors')->all();

        $this->assertNotEmpty($pesan);

        foreach ($pesan as $p) {
            $this->assertStringNotContainsString(
                'validation.',
                $p,
                'Pesan validasi masih berupa kunci terjemahan mentah: ' . $p
            );
        }

        $this->assertContains('Nama lengkap wajib diisi.', $pesan);
        $this->assertContains('Seksi/unit wajib diisi.', $pesan);
    }

    // --- Papan pantau absensi admin ---

    public function test_absensi_admin_menampilkan_magang_yang_belum_absen(): void
    {
        \Illuminate\Support\Carbon::setTestNow('2026-08-24 10:00:00'); // Senin

        $admin = User::factory()->admin()->create();

        $hadir = User::factory()->create(['name' => 'Sudah Absen', 'seksi' => \App\Enums\UserSeksi::ASTIK]);
        User::factory()->create(['name' => 'Belum Absen', 'seksi' => \App\Enums\UserSeksi::ASTIK]);

        \App\Models\Absensi::factory()->for($hadir)->create([
            'checked_in_at' => now()->setTime(9, 30),
            'checked_in_status' => \App\Enums\AbsensiStatus::MasukTelat,
            'checked_out_at' => null,
        ]);

        $response = $this->actingAs($admin)->get('/admin/absensi')->assertOk();

        $this->assertSame(2, $response->viewData('totalMagang'));
        $this->assertSame(1, $response->viewData('hadir'));
        $this->assertSame(1, $response->viewData('belumAbsen'));
        $this->assertSame(1, $response->viewData('telat'));
        $this->assertSame(1, $response->viewData('belumPulang'));

        // Yang belum absen tetap muncul sebagai baris, bukan hilang dari tabel.
        $html = $response->getContent();
        $this->assertStringContainsString('Belum Absen', $html);
        $this->assertStringContainsString('Sudah Absen', $html);

        \Illuminate\Support\Carbon::setTestNow();
    }

    public function test_absensi_admin_bukan_lagi_salinan_halaman_user(): void
    {
        $admin = User::factory()->admin()->create();
        $magang = User::factory()->create();

        $html = $this->actingAs($admin)->get('/admin/absensi')->assertOk()->getContent();

        // Halaman absensi tidak boleh menawarkan aksi manajemen user.
        $this->assertStringNotContainsString(route('admin-user-create'), $html);
        $this->assertStringNotContainsString(route('admin-user-destroy', $magang->id), $html);
        $this->assertStringNotContainsString(route('admin-user-edit', $magang->id), $html);
    }

    public function test_absensi_admin_bisa_pilih_tanggal_lain(): void
    {
        $admin = User::factory()->admin()->create();
        $magang = User::factory()->create(['name' => 'Peserta']);

        \App\Models\Absensi::factory()->for($magang)->create([
            'created_at' => now()->subDays(3),
            'checked_in_at' => now()->subDays(3)->setTime(9, 0),
        ]);

        $kemarin3 = now()->subDays(3)->format('Y-m-d');

        $response = $this->actingAs($admin)
            ->get('/admin/absensi?tanggal=' . $kemarin3)->assertOk();

        $this->assertSame(1, $response->viewData('hadir'));
        $this->assertSame($kemarin3, $response->viewData('tanggal')->format('Y-m-d'));

        // Hari ini orang yang sama belum absen.
        $hariIni = $this->actingAs($admin)->get('/admin/absensi')->assertOk();
        $this->assertSame(0, $hariIni->viewData('hadir'));
    }

    public function test_absensi_admin_hanya_menghitung_magang_aktif(): void
    {
        $admin = User::factory()->admin()->create();

        User::factory()->create(['name' => 'Masih Magang']);
        User::factory()->create([
            'name' => 'Sudah Lulus',
            'tanggal_akhir_magang' => now()->subMonth(),
        ]);

        $response = $this->actingAs($admin)->get('/admin/absensi')->assertOk();

        $this->assertSame(1, $response->viewData('totalMagang'));
        $this->assertStringNotContainsString('Sudah Lulus', $response->getContent());
    }

    // --- Periode magang vs tanggal yang dipantau ---

    /**
     * Magang yang baru mulai Agustus tidak boleh tercatat "Belum Absen" pada
     * tanggal sebelum dia masuk — saat itu dia memang belum jadi peserta.
     */
    public function test_magang_belum_mulai_tidak_dihitung_bolos(): void
    {
        $admin = User::factory()->admin()->create();

        User::factory()->create([
            'name'                => 'Baru Mulai Agustus',
            'tanggal_awal_magang' => '2026-08-01',
            'tanggal_akhir_magang' => '2026-12-31',
        ]);

        // Juli: belum jadi peserta.
        $juli = $this->actingAs($admin)->get('/admin/absensi?tanggal=2026-07-15')->assertOk();
        $this->assertSame(0, $juli->viewData('totalMagang'));
        $this->assertSame(0, $juli->viewData('belumAbsen'));
        $this->assertStringNotContainsString('Baru Mulai Agustus', $juli->getContent());

        // Agustus: sudah jadi peserta, wajar dihitung belum absen.
        $agustus = $this->actingAs($admin)->get('/admin/absensi?tanggal=2026-08-10')->assertOk();
        $this->assertSame(1, $agustus->viewData('totalMagang'));
        $this->assertSame(1, $agustus->viewData('belumAbsen'));
        $this->assertStringContainsString('Baru Mulai Agustus', $agustus->getContent());
    }

    /**
     * Kebalikannya: magang yang sudah selesai harus tetap muncul saat admin
     * membuka tanggal ketika dia masih aktif, kalau tidak riwayatnya bolong.
     */
    public function test_magang_sudah_selesai_tetap_muncul_di_tanggal_lamanya(): void
    {
        $admin = User::factory()->admin()->create();

        $alumni = User::factory()->create([
            'name'                 => 'Alumni Juni',
            'tanggal_awal_magang'  => '2026-01-01',
            'tanggal_akhir_magang' => '2026-06-30',
        ]);

        \App\Models\Absensi::factory()->for($alumni)->create([
            'created_at'    => '2026-05-20 09:00:00',
            'checked_in_at' => '2026-05-20 09:00:00',
        ]);

        $mei = $this->actingAs($admin)->get('/admin/absensi?tanggal=2026-05-20')->assertOk();
        $this->assertSame(1, $mei->viewData('totalMagang'));
        $this->assertSame(1, $mei->viewData('hadir'));

        // Tapi di tanggal setelah masa magangnya, dia tidak lagi dipantau.
        $sekarang = $this->actingAs($admin)->get('/admin/absensi?tanggal=2026-08-20')->assertOk();
        $this->assertSame(0, $sekarang->viewData('totalMagang'));
    }

    public function test_magang_tanpa_tanggal_dianggap_selalu_dalam_periode(): void
    {
        $admin = User::factory()->admin()->create();

        User::factory()->create([
            'name'                 => 'Tanpa Periode',
            'tanggal_awal_magang'  => null,
            'tanggal_akhir_magang' => null,
        ]);

        foreach (['2020-01-01', '2026-08-20', '2030-12-31'] as $tanggal) {
            $this->assertSame(
                1,
                $this->actingAs($admin)->get('/admin/absensi?tanggal=' . $tanggal)
                    ->assertOk()->viewData('totalMagang'),
                "Gagal pada tanggal {$tanggal}"
            );
        }
    }

    public function test_beranda_admin_juga_menghormati_tanggal_mulai(): void
    {
        $admin = User::factory()->admin()->create();

        User::factory()->create([
            'name'                => 'Mulai Bulan Depan',
            'tanggal_awal_magang' => now()->addMonth(),
        ]);
        User::factory()->create([
            'name'                => 'Sudah Jalan',
            'tanggal_awal_magang' => now()->subMonth(),
        ]);

        $response = $this->actingAs($admin)->get('/')->assertOk();

        $this->assertSame(1, $response->viewData('totalMagang'));
        $this->assertStringNotContainsString('Mulai Bulan Depan', $response->getContent());
    }

    // --- Akhir pekan bukan hari absen ---

    public function test_akhir_pekan_tidak_dihitung_bolos(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->count(3)->create();

        // 2026-08-22 = Sabtu, 2026-08-23 = Minggu, 2026-08-24 = Senin.
        foreach (['2026-08-22', '2026-08-23'] as $libur) {
            $r = $this->actingAs($admin)->get('/admin/absensi?tanggal=' . $libur)->assertOk();

            $this->assertFalse($r->viewData('hariKerja'), "{$libur} seharusnya hari libur.");
            $this->assertSame(0, $r->viewData('belumAbsen'), "{$libur} tidak boleh menandai bolos.");
            $this->assertSame(3, $r->viewData('totalMagang'));
            $this->assertStringNotContainsString('Belum Absen</span>', $r->getContent());
        }

        $senin = $this->actingAs($admin)->get('/admin/absensi?tanggal=2026-08-24')->assertOk();
        $this->assertTrue($senin->viewData('hariKerja'));
        $this->assertSame(3, $senin->viewData('belumAbsen'));
    }

    public function test_absensi_di_akhir_pekan_tetap_tercatat(): void
    {
        $admin = User::factory()->admin()->create();
        $lembur = User::factory()->create(['name' => 'Si Lembur']);

        \App\Models\Absensi::factory()->for($lembur)->create([
            'created_at'    => '2026-08-22 09:00:00',
            'checked_in_at' => '2026-08-22 09:00:00',
        ]);

        $r = $this->actingAs($admin)->get('/admin/absensi?tanggal=2026-08-22')->assertOk();

        // Libur bukan berarti data absen disembunyikan.
        $this->assertSame(1, $r->viewData('hadir'));
        $this->assertStringContainsString('Si Lembur', $r->getContent());
    }

    public function test_beranda_admin_di_akhir_pekan(): void
    {
        \Illuminate\Support\Carbon::setTestNow('2026-08-23 10:00:00'); // Minggu

        $admin = User::factory()->admin()->create();
        User::factory()->count(2)->create();

        $r = $this->actingAs($admin)->get('/')->assertOk();

        $this->assertFalse($r->viewData('hariKerja'));
        $this->assertSame(0, $r->viewData('belumAbsen'));
        $this->assertCount(0, $r->viewData('daftarBelum'));
        $this->assertStringContainsString('Hari libur', $r->getContent());

        \Illuminate\Support\Carbon::setTestNow();
    }

    public function test_jadwal_kerja_mengenali_akhir_pekan(): void
    {
        $jadwal = app(\App\Services\JadwalKerja::class);

        $this->assertTrue($jadwal->hariKerja(\Illuminate\Support\Carbon::parse('2026-08-24')));  // Senin
        $this->assertTrue($jadwal->hariKerja(\Illuminate\Support\Carbon::parse('2026-08-28')));  // Jumat
        $this->assertFalse($jadwal->hariKerja(\Illuminate\Support\Carbon::parse('2026-08-22'))); // Sabtu
        $this->assertFalse($jadwal->hariKerja(\Illuminate\Support\Carbon::parse('2026-08-23'))); // Minggu
    }

    // --- Menu navigasi tunggal ---

    public function test_navbar_tidak_lagi_punya_ubin_profil(): void
    {
        foreach ([User::factory()->create(), User::factory()->admin()->create()] as $user) {
            $html = $this->actingAs($user)->get('/')->assertOk()->getContent();

            // Ubin navbar hilang...
            $this->assertStringNotContainsString(
                'btn-inner--icon d-block pt-2">Profil<',
                $html,
                'Ubin Profil masih ada di grid menu.'
            );

            // ...tapi Profil tetap dijangkau lewat dropdown avatar.
            $this->assertStringContainsString('href="' . route('profil') . '"', $html);
            $this->assertStringContainsString('<span>Profil</span>', $html);
        }
    }

    public function test_navbar_tidak_punya_blok_admin_menu_terpisah(): void
    {
        $html = $this->actingAs(User::factory()->admin()->create())
            ->get('/')->assertOk()->getContent();

        $this->assertStringNotContainsString('Admin Menu', $html);

        // Menu admin tetap ada, hanya menyatu di grid yang sama.
        foreach ([route('admin-user'), route('admin-absensi'), route('admin-laporan.index')] as $url) {
            $this->assertStringContainsString('href="' . $url . '"', $html);
        }
    }

    public function test_magang_tidak_melihat_menu_admin(): void
    {
        $html = $this->actingAs(User::factory()->create())
            ->get('/')->assertOk()->getContent();

        foreach ([route('admin-user'), route('admin-absensi'), route('admin-laporan.index')] as $url) {
            $this->assertStringNotContainsString('href="' . $url . '"', $html);
        }
    }

    // --- Halaman laporan magang (admin) ---

    public function test_daftar_laporan_hanya_magang_aktif(): void
    {
        $admin = User::factory()->admin()->create();

        User::factory()->create(['name' => 'Magang Jalan']);
        User::factory()->create([
            'name' => 'Alumni Lama',
            'tanggal_akhir_magang' => now()->subMonths(3),
        ]);
        User::factory()->create([
            'name' => 'Belum Mulai',
            'tanggal_awal_magang' => now()->addMonth(),
        ]);

        $r = $this->actingAs($admin)->get('/admin/laporan-magangs')->assertOk();

        $this->assertSame(1, $r->viewData('totalMagang'));

        $html = $r->getContent();
        $this->assertStringContainsString('Magang Jalan', $html);
        $this->assertStringNotContainsString('Alumni Lama', $html);
        $this->assertStringNotContainsString('Belum Mulai', $html);
    }

    public function test_tombol_export_hilang_dari_daftar_laporan(): void
    {
        $admin = User::factory()->admin()->create();
        $magang = User::factory()->create();

        $html = $this->actingAs($admin)->get('/admin/laporan-magangs')->assertOk()->getContent();

        $this->assertStringNotContainsString(route('admin-laporan.export', $magang->id), $html);
        $this->assertStringContainsString(route('admin-laporan.user', $magang->id), $html);
    }

    public function test_detail_laporan_default_bulan_ini(): void
    {
        \Illuminate\Support\Carbon::setTestNow('2026-08-15 10:00:00');

        $admin = User::factory()->admin()->create();
        $magang = User::factory()->create();

        LaporanKegiatan::create([
            'user_id' => $magang->id, 'tanggal' => '2026-08-05',
            'detail_kegiatan' => 'Kegiatan Agustus', 'lokasi' => 'Kantor', 'bulan' => 'Agustus 2026',
        ]);
        LaporanKegiatan::create([
            'user_id' => $magang->id, 'tanggal' => '2026-06-05',
            'detail_kegiatan' => 'Kegiatan Juni', 'lokasi' => 'Kantor', 'bulan' => 'Juni 2026',
        ]);

        $r = $this->actingAs($admin)->get("/admin/laporan-magangs/{$magang->id}")->assertOk();

        $this->assertCount(1, $r->viewData('laporan'), 'Detail tidak dibatasi bulan berjalan.');
        $this->assertSame(8, $r->viewData('selectedMonth'));

        $html = $r->getContent();
        $this->assertStringContainsString('Kegiatan Agustus', $html);
        $this->assertStringNotContainsString('Kegiatan Juni', $html);

        \Illuminate\Support\Carbon::setTestNow();
    }

    public function test_detail_laporan_bisa_lihat_semua_periode(): void
    {
        \Illuminate\Support\Carbon::setTestNow('2026-08-15 10:00:00');

        $admin = User::factory()->admin()->create();
        $magang = User::factory()->create();

        foreach (['2026-08-05', '2026-06-05'] as $tanggal) {
            LaporanKegiatan::create([
                'user_id' => $magang->id, 'tanggal' => $tanggal,
                'detail_kegiatan' => 'Kegiatan ' . $tanggal, 'lokasi' => 'Kantor', 'bulan' => 'x',
            ]);
        }

        $r = $this->actingAs($admin)
            ->get("/admin/laporan-magangs/{$magang->id}?month=all")->assertOk();

        $this->assertCount(2, $r->viewData('laporan'));

        \Illuminate\Support\Carbon::setTestNow();
    }

    public function test_detail_laporan_menampilkan_gambar_langsung(): void
    {
        \Illuminate\Support\Carbon::setTestNow('2026-08-15 10:00:00');

        $admin = User::factory()->admin()->create();
        $magang = User::factory()->create();

        LaporanKegiatan::create([
            'user_id' => $magang->id, 'tanggal' => '2026-08-05',
            'detail_kegiatan' => 'x', 'lokasi' => 'y', 'bulan' => 'Agustus 2026',
            'dokumentasi' => 'dokumentasi/bukti.jpg',
        ]);

        $html = $this->actingAs($admin)
            ->get("/admin/laporan-magangs/{$magang->id}")->assertOk()->getContent();

        $this->assertStringContainsString('<img src="' . asset('storage/dokumentasi/bukti.jpg') . '"', $html);
        $this->assertStringNotContainsString('btn btn-sm btn-info">Lihat<', $html);

        \Illuminate\Support\Carbon::setTestNow();
    }

    public function test_export_ada_di_halaman_detail_dan_ikut_periode(): void
    {
        \Illuminate\Support\Carbon::setTestNow('2026-08-15 10:00:00');

        $admin = User::factory()->admin()->create();
        $magang = User::factory()->create();

        $html = $this->actingAs($admin)
            ->get("/admin/laporan-magangs/{$magang->id}")->assertOk()->getContent();

        $this->assertStringContainsString(
            route('admin-laporan.export', $magang->id) . '?month=8&year=2026',
            $html
        );

        \Illuminate\Support\Carbon::setTestNow();
    }

    // --- Daftar laporan mengikuti periode yang difilter ---

    public function test_daftar_laporan_mengikuti_bulan_yang_difilter(): void
    {
        \Illuminate\Support\Carbon::setTestNow('2026-08-15 10:00:00');

        $admin = User::factory()->admin()->create();

        // Angkatan lama: Januari-Juni.
        $lama = User::factory()->create([
            'name' => 'Angkatan Juni',
            'tanggal_awal_magang'  => '2026-01-01',
            'tanggal_akhir_magang' => '2026-06-30',
        ]);
        // Angkatan sekarang: Agustus-Desember.
        User::factory()->create([
            'name' => 'Angkatan Agustus',
            'tanggal_awal_magang'  => '2026-08-01',
            'tanggal_akhir_magang' => '2026-12-31',
        ]);

        LaporanKegiatan::create([
            'user_id' => $lama->id, 'tanggal' => '2026-06-10',
            'detail_kegiatan' => 'x', 'lokasi' => 'y', 'bulan' => 'Juni 2026',
        ]);

        // Tanpa filter: angkatan yang sedang berjalan.
        $sekarang = $this->actingAs($admin)->get('/admin/laporan-magangs')->assertOk();
        $this->assertSame(1, $sekarang->viewData('totalMagang'));
        $this->assertStringContainsString('Angkatan Agustus', $sekarang->getContent());

        // Filter Juni: angkatan yang aktif Juni, berikut laporannya.
        $juni = $this->actingAs($admin)
            ->get('/admin/laporan-magangs?month=6&year=2026')->assertOk();

        $this->assertSame(1, $juni->viewData('totalMagang'));
        $this->assertSame(1, $juni->viewData('totalLaporan'));

        $html = $juni->getContent();
        $this->assertStringContainsString('Angkatan Juni', $html);
        $this->assertStringNotContainsString('Angkatan Agustus', $html);

        \Illuminate\Support\Carbon::setTestNow();
    }

    public function test_filter_tahun_saja_mencakup_semua_angkatan_tahun_itu(): void
    {
        \Illuminate\Support\Carbon::setTestNow('2026-08-15 10:00:00');

        $admin = User::factory()->admin()->create();

        User::factory()->create([
            'name' => 'Angkatan Juni',
            'tanggal_awal_magang'  => '2026-01-01',
            'tanggal_akhir_magang' => '2026-06-30',
        ]);
        User::factory()->create([
            'name' => 'Angkatan Agustus',
            'tanggal_awal_magang'  => '2026-08-01',
            'tanggal_akhir_magang' => '2026-12-31',
        ]);
        User::factory()->create([
            'name' => 'Angkatan Tahun Lalu',
            'tanggal_awal_magang'  => '2025-01-01',
            'tanggal_akhir_magang' => '2025-12-31',
        ]);

        $r = $this->actingAs($admin)->get('/admin/laporan-magangs?year=2026')->assertOk();

        $this->assertSame(2, $r->viewData('totalMagang'));
        $this->assertStringNotContainsString('Angkatan Tahun Lalu', $r->getContent());

        \Illuminate\Support\Carbon::setTestNow();
    }

    public function test_periode_filter_terbawa_ke_halaman_detail(): void
    {
        \Illuminate\Support\Carbon::setTestNow('2026-08-15 10:00:00');

        $admin = User::factory()->admin()->create();
        $magang = User::factory()->create([
            'tanggal_awal_magang'  => '2026-01-01',
            'tanggal_akhir_magang' => '2026-12-31',
        ]);

        LaporanKegiatan::create([
            'user_id' => $magang->id, 'tanggal' => '2026-06-10',
            'detail_kegiatan' => 'Kegiatan Juni', 'lokasi' => 'y', 'bulan' => 'Juni 2026',
        ]);

        $html = $this->actingAs($admin)
            ->get('/admin/laporan-magangs?month=6&year=2026')->assertOk()->getContent();

        // Tautan "Lihat Laporan" membawa periode yang sedang difilter.
        // e() dipakai karena Blade meng-escape "&" jadi "&amp;" di atribut href.
        $this->assertStringContainsString(
            e(route('admin-laporan.user', ['user' => $magang->id, 'month' => 6, 'year' => 2026])),
            $html
        );

        $detail = $this->actingAs($admin)
            ->get("/admin/laporan-magangs/{$magang->id}?month=6&year=2026")->assertOk();

        $this->assertCount(1, $detail->viewData('laporan'));
        $this->assertStringContainsString('Kegiatan Juni', $detail->getContent());

        \Illuminate\Support\Carbon::setTestNow();
    }

    public function test_magang_tanpa_periode_selalu_muncul_di_filter_bulan_apa_pun(): void
    {
        $admin = User::factory()->admin()->create();

        User::factory()->create([
            'name' => 'Tanpa Periode',
            'tanggal_awal_magang'  => null,
            'tanggal_akhir_magang' => null,
        ]);

        foreach (['?month=1&year=2020', '?month=6&year=2026', '?year=2030'] as $q) {
            $this->assertSame(
                1,
                $this->actingAs($admin)->get('/admin/laporan-magangs' . $q)
                    ->assertOk()->viewData('totalMagang'),
                "Gagal pada filter {$q}"
            );
        }
    }

    // --- Footer ---

    public function test_footer_bersih_dari_tautan_template(): void
    {
        $html = $this->actingAs(User::factory()->create())
            ->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('&copy; 2026 by TomGan', $html);

        foreach (['HideKy', '>Support<', '>Terms<', '>Privacy<'] as $sisa) {
            $this->assertStringNotContainsString($sisa, $html, "Masih ada '{$sisa}' di footer.");
        }
    }

    // --- Tim Saya (rekan satu unit) ---

    public function test_magang_melihat_rekan_satu_unit_saja(): void
    {
        $saya = User::factory()->create([
            'name' => 'Saya Sendiri', 'seksi' => \App\Enums\UserSeksi::ASTIK,
        ]);
        User::factory()->create(['name' => 'Rekan ASTIK', 'seksi' => \App\Enums\UserSeksi::ASTIK]);
        User::factory()->create(['name' => 'Orang KIP', 'seksi' => \App\Enums\UserSeksi::KIP]);

        $response = $this->actingAs($saya)->get('/tim')->assertOk();

        // Tim Saya hanya berisi unit sendiri. Orang KIP tetap tampil di
        // halaman, tapi di bagian Lintas Tim — bukan di Tim Saya.
        $rekan = $response->viewData('rekan');
        $this->assertCount(1, $rekan);
        $this->assertSame('Rekan ASTIK', $rekan->first()->name);
        $this->assertNotContains('Orang KIP', $rekan->pluck('name')->all());
    }

    public function test_tim_tidak_menampilkan_alumni_atau_yang_belum_mulai(): void
    {
        $saya = User::factory()->create(['seksi' => \App\Enums\UserSeksi::ASTIK]);

        User::factory()->create([
            'name' => 'Rekan Aktif', 'seksi' => \App\Enums\UserSeksi::ASTIK,
        ]);
        User::factory()->create([
            'name' => 'Alumni ASTIK', 'seksi' => \App\Enums\UserSeksi::ASTIK,
            'tanggal_akhir_magang' => now()->subMonth(),
        ]);
        User::factory()->create([
            'name' => 'Calon ASTIK', 'seksi' => \App\Enums\UserSeksi::ASTIK,
            'tanggal_awal_magang' => now()->addMonth(),
        ]);

        $html = $this->actingAs($saya)->get('/tim')->assertOk()->getContent();

        $this->assertStringContainsString('Rekan Aktif', $html);
        $this->assertStringNotContainsString('Alumni ASTIK', $html);
        $this->assertStringNotContainsString('Calon ASTIK', $html);
    }

    public function test_tim_tidak_menampilkan_admin_maupun_diri_sendiri(): void
    {
        $saya = User::factory()->create([
            'name' => 'Saya Sendiri', 'seksi' => \App\Enums\UserSeksi::ASTIK,
        ]);
        User::factory()->admin()->create([
            'name' => 'Pak Admin', 'seksi' => \App\Enums\UserSeksi::ASTIK,
        ]);

        $response = $this->actingAs($saya)->get('/tim')->assertOk();

        $this->assertCount(0, $response->viewData('rekan'));
        $this->assertStringNotContainsString('Pak Admin', $response->getContent());
    }

    /**
     * Halaman perkenalan tidak boleh jadi jalan membaca data identitas rekan.
     */
    public function test_tim_tidak_membocorkan_data_sensitif(): void
    {
        $saya = User::factory()->create(['seksi' => \App\Enums\UserSeksi::ASTIK]);

        User::factory()->create([
            'name'            => 'Rekan ASTIK',
            'seksi'           => \App\Enums\UserSeksi::ASTIK,
            'email'           => 'rahasia@test.id',
            'identity_number' => '9988776655',
            'alamat'          => 'Jl. Rahasia No. 7',
            'tanggal_lahir'   => '2003-05-17',
        ]);

        $html = $this->actingAs($saya)->get('/tim')->assertOk()->getContent();

        foreach (['rahasia@test.id', '9988776655', 'Jl. Rahasia No. 7', '2003-05-17'] as $bocor) {
            $this->assertStringNotContainsString($bocor, $html, "Data sensitif '{$bocor}' tampil di halaman tim.");
        }
    }

    public function test_tim_menampilkan_kontak_yang_dibagikan(): void
    {
        $saya = User::factory()->create(['seksi' => \App\Enums\UserSeksi::ASTIK]);

        User::factory()->create([
            'name'      => 'Rekan Sosial',
            'seksi'     => \App\Enums\UserSeksi::ASTIK,
            'no_telp'   => '081234567890',
            'instagram' => 'https://instagram.com/rekan',
            'linkedin'  => 'https://linkedin.com/in/rekan',
        ]);

        $html = $this->actingAs($saya)->get('/tim')->assertOk()->getContent();

        // Nomor 08... diubah ke format internasional untuk tautan wa.me.
        $this->assertStringContainsString('https://wa.me/6281234567890', $html);
        $this->assertStringContainsString('https://instagram.com/rekan', $html);
        $this->assertStringContainsString('https://linkedin.com/in/rekan', $html);
    }

    public function test_magang_tanpa_unit_dapat_penjelasan(): void
    {
        $saya = User::factory()->create(['seksi' => null]);

        $response = $this->actingAs($saya)->get('/tim')->assertOk();

        $this->assertCount(0, $response->viewData('rekan'));
        $this->assertStringContainsString('belum ditempatkan di unit', $response->getContent());
    }

    public function test_admin_tidak_punya_menu_tim(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get('/tim')
            ->assertRedirect(route('home'));

        $html = $this->actingAs(User::factory()->admin()->create())
            ->get('/')->assertOk()->getContent();

        $this->assertStringNotContainsString('href="' . route('tim') . '"', $html);
    }

    public function test_magang_punya_menu_tim(): void
    {
        $html = $this->actingAs(User::factory()->create())
            ->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('href="' . route('tim') . '"', $html);
    }

    // --- Halaman login ---

    public function test_halaman_login_memuat_css_dan_branding(): void
    {
        $html = $this->get('/login')->assertOk()->getContent();

        // @stack('styles') sempat tidak ada di layout, sehingga seluruh CSS
        // yang di-push view dibuang diam-diam.
        $this->assertStringContainsString('.login-split', $html, 'CSS login tidak ikut termuat.');

        $this->assertStringContainsString('Sistem Magang', $html);
        $this->assertStringContainsString(asset('images/logo kominfotik.png'), $html);
    }

    public function test_form_login_lengkap(): void
    {
        $html = $this->get('/login')->assertOk()->getContent();

        foreach (['name="email"', 'name="password"', 'name="captcha"', 'name="_token"'] as $field) {
            $this->assertStringContainsString($field, $html);
        }

        $this->assertStringContainsString('id="btn-login"', $html);
        $this->assertStringContainsString('id="button-refresh-captcha"', $html);
    }

    /**
     * Handler lama memanggil e.preventDefault() tanpa menerima parameter e,
     * jadi tombol refresh captcha selalu melempar ReferenceError.
     */
    public function test_tombol_refresh_captcha_punya_parameter_event(): void
    {
        $html = $this->get('/login')->assertOk()->getContent();

        $this->assertStringContainsString("on('click', function (e)", $html);
        $this->assertStringNotContainsString("click(function () {\n        e.preventDefault", $html);
    }

    public function test_refresh_captcha_mengembalikan_gambar_baru(): void
    {
        $response = $this->get('/refresh-captcha')->assertOk();

        $this->assertNotEmpty($response->json('captcha'));
    }

    public function test_login_gagal_mengembalikan_pesan_yang_bisa_ditampilkan(): void
    {
        User::factory()->create(['email' => 'ada@test.id', 'password' => 'benar123456']);

        // 401 — kredensial salah.
        $salah = $this->postJson('/login-attempt', [
            'email' => 'ada@test.id', 'password' => 'salah', 'captcha' => 'x',
        ])->assertStatus(401);
        $this->assertNotEmpty($salah->json('error'));

        // 403 — masa magang berakhir. Keduanya memakai kunci "error" yang sama
        // supaya frontend bisa menampilkan pesan aslinya, bukan teks generik.
        User::factory()->create([
            'email' => 'lewat@test.id', 'password' => 'benar123456',
            'tanggal_akhir_magang' => now()->subMonth(),
        ]);

        $lewat = $this->postJson('/login-attempt', [
            'email' => 'lewat@test.id', 'password' => 'benar123456', 'captcha' => 'x',
        ])->assertStatus(403);

        $this->assertStringContainsString('Masa magang', $lewat->json('error'));
    }

    public function test_user_login_tidak_bisa_membuka_halaman_login(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/login')
            ->assertRedirect(route('home'));
    }

    // --- Lintas Tim ---

    public function test_lintas_tim_menampilkan_unit_lain_tanpa_unit_sendiri(): void
    {
        $saya = User::factory()->create(['seksi' => \App\Enums\UserSeksi::ASTIK]);

        User::factory()->create(['name' => 'Rekan ASTIK', 'seksi' => \App\Enums\UserSeksi::ASTIK]);
        User::factory()->create(['name' => 'Orang ID', 'seksi' => \App\Enums\UserSeksi::ID]);
        User::factory()->create(['name' => 'Orang KIP', 'seksi' => \App\Enums\UserSeksi::KIP]);

        $response = $this->actingAs($saya)->get('/tim')->assertOk();

        // Tim Saya hanya ASTIK; Lintas Tim hanya unit lain.
        $this->assertCount(1, $response->viewData('rekan'));
        $this->assertCount(2, $response->viewData('lintas'));

        $namaLintas = $response->viewData('lintas')->pluck('name')->all();
        $this->assertContains('Orang ID', $namaLintas);
        $this->assertContains('Orang KIP', $namaLintas);
        $this->assertNotContains('Rekan ASTIK', $namaLintas);
    }

    public function test_lintas_tim_bisa_difilter_per_unit(): void
    {
        $saya = User::factory()->create(['seksi' => \App\Enums\UserSeksi::ASTIK]);

        User::factory()->create(['name' => 'Orang ID', 'seksi' => \App\Enums\UserSeksi::ID]);
        User::factory()->create(['name' => 'Orang KIP', 'seksi' => \App\Enums\UserSeksi::KIP]);

        $response = $this->actingAs($saya)
            ->get('/tim?seksi=' . \App\Enums\UserSeksi::KIP->value)->assertOk();

        $lintas = $response->viewData('lintas');
        $this->assertCount(1, $lintas);
        $this->assertSame('Orang KIP', $lintas->first()->name);
        $this->assertSame(\App\Enums\UserSeksi::KIP, $response->viewData('seksiDipilih'));
    }

    /**
     * Memilih unit sendiri di filter Lintas Tim tidak boleh menggandakan
     * daftar Tim Saya; diperlakukan sebagai "semua unit lain".
     */
    public function test_lintas_tim_mengabaikan_filter_unit_sendiri(): void
    {
        $saya = User::factory()->create(['seksi' => \App\Enums\UserSeksi::ASTIK]);

        User::factory()->create(['name' => 'Rekan ASTIK', 'seksi' => \App\Enums\UserSeksi::ASTIK]);
        User::factory()->create(['name' => 'Orang ID', 'seksi' => \App\Enums\UserSeksi::ID]);

        $response = $this->actingAs($saya)
            ->get('/tim?seksi=' . \App\Enums\UserSeksi::ASTIK->value)->assertOk();

        $this->assertNull($response->viewData('seksiDipilih'));
        $this->assertNotContains('Rekan ASTIK', $response->viewData('lintas')->pluck('name')->all());

        // Unit sendiri juga tidak ditawarkan di dropdown.
        $this->assertNotContains(
            \App\Enums\UserSeksi::ASTIK,
            $response->viewData('daftarSeksi')->all()
        );
    }

    public function test_lintas_tim_tidak_membocorkan_data_sensitif(): void
    {
        $saya = User::factory()->create(['seksi' => \App\Enums\UserSeksi::ASTIK]);

        User::factory()->create([
            'name'            => 'Orang ID',
            'seksi'           => \App\Enums\UserSeksi::ID,
            'email'           => 'lintas@rahasia.id',
            'identity_number' => '5544332211',
            'alamat'          => 'Jl. Tersembunyi No. 9',
            'tanggal_lahir'   => '2002-11-30',
        ]);

        $html = $this->actingAs($saya)->get('/tim')->assertOk()->getContent();

        $this->assertStringContainsString('Orang ID', $html);

        foreach (['lintas@rahasia.id', '5544332211', 'Jl. Tersembunyi No. 9', '2002-11-30'] as $bocor) {
            $this->assertStringNotContainsString($bocor, $html, "Data sensitif '{$bocor}' tampil di Lintas Tim.");
        }
    }

    public function test_lintas_tim_hanya_magang_aktif_dan_bukan_admin(): void
    {
        $saya = User::factory()->create(['seksi' => \App\Enums\UserSeksi::ASTIK]);

        User::factory()->create(['name' => 'ID Aktif', 'seksi' => \App\Enums\UserSeksi::ID]);
        User::factory()->create([
            'name' => 'ID Alumni', 'seksi' => \App\Enums\UserSeksi::ID,
            'tanggal_akhir_magang' => now()->subMonth(),
        ]);
        User::factory()->admin()->create(['name' => 'Admin ID', 'seksi' => \App\Enums\UserSeksi::ID]);

        $nama = $this->actingAs($saya)->get('/tim')->assertOk()
            ->viewData('lintas')->pluck('name')->all();

        $this->assertSame(['ID Aktif'], $nama);
    }

    public function test_magang_tanpa_unit_tetap_bisa_lihat_lintas_tim(): void
    {
        $saya = User::factory()->create(['seksi' => null]);

        User::factory()->create(['name' => 'Orang KIP', 'seksi' => \App\Enums\UserSeksi::KIP]);

        $response = $this->actingAs($saya)->get('/tim')->assertOk();

        $this->assertCount(0, $response->viewData('rekan'));
        $this->assertCount(1, $response->viewData('lintas'));
        $this->assertStringContainsString('Orang KIP', $response->getContent());
    }
}
