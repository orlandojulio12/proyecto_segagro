<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_ver_sus_notificaciones(): void
    {
        $user = User::factory()->create();

        // Crear notificación manual
        DatabaseNotification::create([
            'id'              => \Illuminate\Support\Str::uuid(),
            'type'            => 'App\Notifications\PqrExpirationNotification',
            'notifiable_type' => $user->getMorphClass(),
            'notifiable_id'   => $user->id,
            'data'            => [
                'pqr_id'          => 1,
                'title'           => 'PQR de prueba',
                'tipo'            => 'PQR',
                'tiempo_restante' => '1 días',
                'color'           => '#F44336',
                'deadline'        => '30/06/2026 08:00',
                'mensaje'         => 'La PQR "PQR de prueba" vence en 1 días.',
            ],
            'read_at'    => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/notifications');

        $response->assertStatus(200);
        $response->assertSee('PQR de prueba');
    }

    public function test_marcar_todas_como_leidas(): void
    {
        $user = User::factory()->create();

        DatabaseNotification::create([
            'id'              => \Illuminate\Support\Str::uuid(),
            'type'            => 'App\Notifications\PqrExpirationNotification',
            'notifiable_type' => $user->getMorphClass(),
            'notifiable_id'   => $user->id,
            'data'            => ['title' => 'Test', 'mensaje' => 'msg'],
            'read_at'         => null,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        $this->actingAs($user)->post('/notifications/mark-all-read');

        $this->assertSame(0, $user->fresh()->unreadNotifications->count());
    }
}
