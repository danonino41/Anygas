<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private Usuario $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = Usuario::factory()->create([
            'rol' => 'administrador',
            'contrasena' => bcrypt('secret'),
        ]);
    }

    public function test_muestra_formulario_login(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Ingresar');
    }

    public function test_login_exitoso_redirige_al_dashboard(): void
    {
        $response = $this->post('/login', [
            'correo' => $this->user->correo,
            'contrasena' => 'secret',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($this->user);
    }

    public function test_login_fallido_muestra_error(): void
    {
        $response = $this->post('/login', [
            'correo' => $this->user->correo,
            'contrasena' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('error_login');
        $this->assertGuest();
    }

    public function test_logout_cierra_sesion(): void
    {
        $this->actingAs($this->user);
        $response = $this->post('/logout');
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
