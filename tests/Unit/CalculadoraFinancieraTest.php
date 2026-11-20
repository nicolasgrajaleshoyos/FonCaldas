<?php

use App\Services\CalculadoraFinanciera;


test('calcula correctamente un credito de cuota fija', function () {

    $calculadora = new CalculadoraFinanciera();

    $resultado = $calculadora->calcularCreditoCuotaFija(
        1_000_000,
        1.25,
        12
    );

    expect($resultado['cuota'])
        ->toBe(90258)
        ->and($resultado['intereses'])
        ->toBe(83100)
        ->and($resultado['total'])
        ->toBe(1083100);
});


test('calcula correctamente un avance a 30 dias', function () {

    $calculadora = new CalculadoraFinanciera();

    $resultado = $calculadora->calcularAvance(
        1_000_000,
        1.60,
        30
    );

    expect($resultado['interes'])
        ->toBe(16000)
        ->and($resultado['total'])
        ->toBe(1016000);
});


test('calcula el ejemplo de ahorro ordinario de foncaldas', function () {

    $calculadora = new CalculadoraFinanciera();

    $resultado = $calculadora->calcularAhorroOrdinario(
        1_000_000,
        3.00,
        30
    );

    expect($resultado['rendimiento'])
        ->toBe(2432)
        ->and($resultado['saldo_final'])
        ->toBe(1002432);
});


test('calcula el ejemplo de cdat de foncaldas', function () {

    $calculadora = new CalculadoraFinanciera();

    $resultado = $calculadora->calcularCdat(
        5_000_000,
        8.50,
        180,
        4
    );

    expect($resultado['rendimiento_bruto'])
        ->toBe(205257)
        ->and($resultado['retencion'])
        ->toBe(8210)
        ->and($resultado['rendimiento_neto'])
        ->toBe(197047)
        ->and($resultado['valor_final'])
        ->toBe(5197047);
});


test('permite credito con tasa cero', function () {

    $calculadora = new CalculadoraFinanciera();

    $resultado = $calculadora->calcularCreditoCuotaFija(
        1_200_000,
        0,
        12
    );

    expect($resultado['cuota'])
        ->toBe(100000)
        ->and($resultado['intereses'])
        ->toBe(0)
        ->and($resultado['total'])
        ->toBe(1200000);
});


test('rechaza capitales menores o iguales a cero', function () {

    $calculadora = new CalculadoraFinanciera();

    $calculadora->calcularCreditoCuotaFija(
        0,
        1.25,
        12
    );

})->throws(
    \InvalidArgumentException::class,
    'El capital debe ser mayor que cero.'
);


test('rechaza periodos menores o iguales a cero', function () {

    $calculadora = new CalculadoraFinanciera();

    $calculadora->calcularAvance(
        1_000_000,
        1.60,
        0
    );

})->throws(
   \InvalidArgumentException::class,
    'El periodo debe ser mayor que cero.'
);
