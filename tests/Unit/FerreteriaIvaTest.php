<?php

namespace Tests\Unit;

use Tests\TestCase;

class FerreteriaIvaTest extends TestCase
{
    private function calcularTotales(int $cantidad, float $precio, int $iva): array
    {
        $sinIva  = $cantidad * $precio;
        $conIva  = $sinIva + ($sinIva * $iva / 100);
        return [
            'total_without_tax' => $sinIva,
            'total_with_tax'    => $conIva,
        ];
    }

    public function test_calculo_iva_cero(): void
    {
        $result = $this->calcularTotales(10, 5000.0, 0);
        $this->assertSame(50000.0, $result['total_without_tax']);
        $this->assertSame(50000.0, $result['total_with_tax']);
    }

    public function test_calculo_iva_19(): void
    {
        $result = $this->calcularTotales(2, 100000.0, 19);
        $this->assertSame(200000.0, $result['total_without_tax']);
        $this->assertSame(238000.0, $result['total_with_tax']);
    }

    public function test_calculo_iva_5(): void
    {
        $result = $this->calcularTotales(4, 25000.0, 5);
        $this->assertSame(100000.0, $result['total_without_tax']);
        $this->assertSame(105000.0, $result['total_with_tax']);
    }

    public function test_calculo_iva_12(): void
    {
        $result = $this->calcularTotales(1, 1000.0, 12);
        $this->assertSame(1000.0, $result['total_without_tax']);
        $this->assertSame(1120.0, $result['total_with_tax']);
    }

    public function test_cantidad_unitaria(): void
    {
        $result = $this->calcularTotales(1, 50000.0, 19);
        $this->assertSame(50000.0, $result['total_without_tax']);
        $this->assertSame(59500.0, $result['total_with_tax']);
    }
}
