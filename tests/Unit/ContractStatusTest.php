<?php

namespace Tests\Unit;

use App\Models\Contract\Contract;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ContractStatusTest extends TestCase
{
    private function makeContract(array $attrs): Contract
    {
        return new Contract($attrs);
    }

    public function test_contrato_activo_cuando_hoy_esta_entre_inicio_y_fin(): void
    {
        $contract = $this->makeContract([
            'start_date'      => Carbon::now()->subDays(10)->toDateString(),
            'initial_end_date' => Carbon::now()->addDays(30)->toDateString(),
            'extension_date'  => null,
            'initial_value'   => 1000000,
            'addition_value'  => null,
        ]);

        $this->assertTrue($contract->is_active);
        $this->assertFalse($contract->is_expired);
        $this->assertFalse($contract->is_pending);
        $this->assertSame('Activo', $contract->status);
        $this->assertSame('bg-success', $contract->status_badge_class);
    }

    public function test_contrato_vencido_cuando_fecha_fin_es_pasada(): void
    {
        $contract = $this->makeContract([
            'start_date'       => Carbon::now()->subDays(60)->toDateString(),
            'initial_end_date' => Carbon::now()->subDays(10)->toDateString(),
            'extension_date'   => null,
            'initial_value'    => 500000,
            'addition_value'   => null,
        ]);

        $this->assertTrue($contract->is_expired);
        $this->assertFalse($contract->is_active);
        $this->assertSame('Vencido', $contract->status);
        $this->assertSame('bg-danger', $contract->status_badge_class);
        $this->assertSame(0, $contract->days_remaining);
    }

    public function test_contrato_pendiente_cuando_inicio_es_futuro(): void
    {
        $contract = $this->makeContract([
            'start_date'       => Carbon::now()->addDays(5)->toDateString(),
            'initial_end_date' => Carbon::now()->addDays(90)->toDateString(),
            'extension_date'   => null,
            'initial_value'    => 2000000,
            'addition_value'   => null,
        ]);

        $this->assertTrue($contract->is_pending);
        $this->assertFalse($contract->is_active);
        $this->assertSame('Pendiente', $contract->status);
        $this->assertSame('bg-warning', $contract->status_badge_class);
    }

    public function test_prorroga_extiende_fecha_fin(): void
    {
        $contract = $this->makeContract([
            'start_date'       => Carbon::now()->subDays(30)->toDateString(),
            'initial_end_date' => Carbon::now()->subDays(5)->toDateString(),
            'extension_date'   => Carbon::now()->addDays(60)->toDateString(),
            'initial_value'    => 1000000,
            'addition_value'   => null,
        ]);

        $this->assertTrue($contract->is_active);
        $this->assertFalse($contract->is_expired);
        $esperada = Carbon::now()->addDays(60)->toDateString();
        $this->assertSame($esperada, $contract->final_end_date->toDateString());
    }

    public function test_valor_total_incluye_adicion(): void
    {
        $contract = $this->makeContract([
            'start_date'       => Carbon::now()->subDays(1)->toDateString(),
            'initial_end_date' => Carbon::now()->addDays(30)->toDateString(),
            'extension_date'   => null,
            'initial_value'    => 1000000,
            'addition_value'   => 250000,
        ]);

        $this->assertSame('1250000.00', $contract->total_value);
    }

    public function test_valor_total_sin_adicion(): void
    {
        $contract = $this->makeContract([
            'start_date'       => Carbon::now()->subDays(1)->toDateString(),
            'initial_end_date' => Carbon::now()->addDays(30)->toDateString(),
            'extension_date'   => null,
            'initial_value'    => 800000,
            'addition_value'   => null,
        ]);

        $this->assertSame('800000.00', $contract->total_value);
    }
}
