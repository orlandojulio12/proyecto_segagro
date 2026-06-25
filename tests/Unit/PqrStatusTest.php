<?php

namespace Tests\Unit;

use App\Models\Complaint\pqr;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PqrStatusTest extends TestCase
{
    private function makePqr(array $attrs): Pqr
    {
        return new Pqr($attrs);
    }

    // ====================== PQR NORMAL ======================

    public function test_pqr_en_tiempo_cuando_tiene_mas_de_6_dias(): void
    {
        $pqr = $this->makePqr([
            'date'      => Carbon::now()->subDays(2)->toDateTimeString(),
            'is_tutela' => false,
            'state'     => false,
        ]);

        $this->assertSame('En tiempo', $pqr->status_text);
        $this->assertSame('#4CAF50', $pqr->color_status);
        $this->assertGreaterThanOrEqual(6, $pqr->days_remaining);
    }

    public function test_pqr_por_vencer_entre_2_y_6_dias(): void
    {
        $pqr = $this->makePqr([
            'date'      => Carbon::now()->subDays(6)->toDateTimeString(),
            'is_tutela' => false,
            'state'     => false,
        ]);

        $this->assertSame('Por vencer', $pqr->status_text);
        $this->assertSame('#FFC107', $pqr->color_status);
    }

    public function test_pqr_urgente_con_1_dia_restante(): void
    {
        // Anclar Carbon::now() a mediodía para que diffInDays sea exactamente 9
        // y no haya ambigüedad por microsegundos en el límite del día.
        $ahora = Carbon::now()->setTime(12, 0, 0, 0);
        Carbon::setTestNow($ahora);

        $pqr = $this->makePqr([
            'date'      => Carbon::now()->subDays(9)->toDateTimeString(),
            'is_tutela' => false,
            'state'     => false,
        ]);

        $this->assertSame('Urgente', $pqr->status_text);
        $this->assertSame('#F44336', $pqr->color_status);

        Carbon::setTestNow(); // Restaurar
    }

    public function test_pqr_vencida_despues_de_10_dias(): void
    {
        $pqr = $this->makePqr([
            'date'      => Carbon::now()->subDays(11)->toDateTimeString(),
            'is_tutela' => false,
            'state'     => false,
        ]);

        $this->assertSame('Vencido', $pqr->status_text);
        $this->assertSame('#B71C1C', $pqr->color_status);
        $this->assertTrue($pqr->is_expired);
    }

    public function test_pqr_deadline_es_10_dias_despues_de_la_fecha(): void
    {
        $fecha = Carbon::now()->subDays(3);
        $pqr = $this->makePqr([
            'date'      => $fecha->toDateTimeString(),
            'is_tutela' => false,
            'state'     => false,
        ]);

        $esperada = Carbon::parse($fecha)->addDays(10)->toDateString();
        $this->assertSame($esperada, $pqr->deadline_date->toDateString());
    }

    // ====================== TUTELA ======================

    public function test_tutela_en_tiempo_con_mas_de_48_horas(): void
    {
        $pqr = $this->makePqr([
            'date'        => Carbon::now()->subHours(10)->toDateTimeString(),
            'is_tutela'   => true,
            'horas_tutela'=> 72,
            'state'       => false,
        ]);

        $this->assertSame('En tiempo', $pqr->status_text);
        $this->assertGreaterThanOrEqual(48, $pqr->days_remaining);
    }

    public function test_tutela_por_vencer_entre_24_y_48_horas(): void
    {
        $pqr = $this->makePqr([
            'date'        => Carbon::now()->subHours(40)->toDateTimeString(),
            'is_tutela'   => true,
            'horas_tutela'=> 72,
            'state'       => false,
        ]);

        $this->assertSame('Por vencer', $pqr->status_text);
    }

    public function test_tutela_vencida(): void
    {
        $pqr = $this->makePqr([
            'date'        => Carbon::now()->subHours(80)->toDateTimeString(),
            'is_tutela'   => true,
            'horas_tutela'=> 72,
            'state'       => false,
        ]);

        $this->assertSame('Vencido', $pqr->status_text);
        $this->assertTrue($pqr->is_expired);
    }

    public function test_tutela_usa_horas_tutela_personalizadas(): void
    {
        $pqr = $this->makePqr([
            'date'        => Carbon::now()->subHours(20)->toDateTimeString(),
            'is_tutela'   => true,
            'horas_tutela'=> 24,
            'state'       => false,
        ]);

        $this->assertSame('Urgente', $pqr->status_text);
    }
}
