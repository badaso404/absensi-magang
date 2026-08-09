<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('');
    }

    public function test_login_dengan_kredensial_benar(): void
    {
        $user = User::factory()->create(['email' => 'magang@kominfotik.test']);

        $this->postJson('/login-attempt', [
            'email'    => 'magang@kominfotik.test',
            'password' => 'password',
            'captcha'  => 'abcd',
        ])->assertOk()->assertJson(['redirect' => route('home')]);

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_dengan_password_salah_ditolak(): void
    {
        User::factory()->create(['email' => 'magang@kominfotik.test']);

        $this->postJson('/login-attempt', [
            'email'    => 'magang@kominfotik.test',
            'password' => 'salah-total',
            'captcha'  => 'abcd',
        ])->assertStatus(401);

        $this->assertGuest();
    }

    /**
     * Regresi: endpoint login sebelumnya tanpa throttle sama sekali.
     */
    public function test_login_dibatasi_lima_percobaan_per_menit(): void
    {
        User::factory()->create(['email' => 'magang@kominfotik.test']);

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/login-attempt', [
                'email'    => 'magang@kominfotik.test',
                'password' => 'salah',
                'captcha'  => 'abcd',
            ])->assertStatus(401);
        }

        $this->postJson('/login-attempt', [
            'email'    => 'magang@kominfotik.test',
            'password' => 'salah',
            'captcha'  => 'abcd',
        ])->assertStatus(429);
    }

    public function test_logout_menghapus_sesi(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/logout')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_user_yang_sudah_login_tidak_bisa_membuka_halaman_login(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/login')
            ->assertRedirect(route('home'));
    }

    /**
     * Regresi: updateProfilPassword dulu juga menyimpan encrypt($password) ke
     * kolom remember_temp, yang reversibel dengan APP_KEY.
     */
    public function test_ganti_password_tidak_menyimpan_password_plaintext(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->postJson('/profil/update-password', [
            'current_password'      => 'password',
            'password'              => 'password-baru',
            'password_confirmation' => 'password-baru',
        ])->assertOk();

        $user->refresh();

        $this->assertNull($user->remember_temp);
        $this->assertTrue(password_verify('password-baru', $user->password));
    }
}
