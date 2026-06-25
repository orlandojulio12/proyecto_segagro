<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_invitado_no_puede_acceder_al_dashboard(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_login_exitoso_redirige_al_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fallido_devuelve_error(): void
    {
        $response = $this->post('/login', [
            'email'    => 'noexiste@test.com',
            'password' => 'incorrecta',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_usuario_sin_permiso_no_puede_ver_pqr(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/pqr/listado');

        // 403 Forbidden o redirect dependiendo del middleware
        $this->assertTrue(
            in_array($response->status(), [302, 403]),
            "Se esperaba 302 o 403, se obtuvo: {$response->status()}"
        );
    }

    public function test_usuario_sin_permiso_no_puede_ver_usuarios(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/users');

        $response->assertStatus(403);
    }

    public function test_perfil_accesible_para_usuario_autenticado(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
    }

    public function test_logout_cierra_sesion(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/logout');

        $this->assertGuest();
    }
}
