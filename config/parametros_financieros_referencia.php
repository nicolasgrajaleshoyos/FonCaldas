<?php

// Referencias de desarrollo del consolidado de los simuladores de FONCALDAS.
return [
    'credito_avances' => [
        'tasa_interes' => 1.60,
        // Límites provisionales usados para desarrollo.
        'plazo_maximo' => 3,
        'porcentaje_maximo_salario' => 50,
    ],
    'credito_libre_inversion' => [
        'tasa_interes_afiliacion_hasta_5' => 1.55,
        'tasa_interes_afiliacion_5_15' => 1.45,
        'tasa_interes_afiliacion_mas_15' => 1.35,
        'plazo_minimo' => 6,
        'plazo_maximo' => 72,
    ],
    'credito_compra_cartera' => [
        'tasa_interes' => 1.30,
        'plazo_minimo' => 6,
        'plazo_maximo' => 72,
    ],
    'credito_vehiculo' => [
        'tasa_interes' => 1.30,
        'plazo_minimo' => 6,
        'plazo_maximo' => 72,
        'porcentaje_financiacion_nuevo' => 80,
        'porcentaje_financiacion_usado' => 70,
        'antiguedad_maxima_usado' => 10,
    ],
    'credito_motocicleta' => [
        'tasa_interes' => 1.35,
        'plazo_minimo' => 6,
        'plazo_maximo' => 72,
        'porcentaje_financiacion_nuevo' => 80,
        'porcentaje_financiacion_usado' => 70,
        'antiguedad_maxima_usado' => 10,
    ],
    'credito_tecnologia' => [
        'tasa_interes' => 1.25,
        'monto_maximo' => 8_000_000,
        'plazo_minimo' => 6,
        'plazo_maximo' => 48,
    ],
    'credito_turismo' => [
        'tasa_interes' => 1.25,
        'monto_maximo' => 8_000_000,
        'plazo_minimo' => 6,
        'plazo_maximo' => 48,
    ],
    'ahorro_ordinario' => [
        'tasa_interes_ea' => 3,
        'monto_minimo' => 50_000,
        'monto_maximo' => 50_000_000,
        'aporte_minimo' => 20_000,
    ],
    'ahorro_cdat' => [
        'tasa_interes_90_dias' => 8,
        'tasa_interes_180_dias' => 8.50,
        'tasa_interes_360_dias' => 9,
        'monto_minimo' => 500_000,
        'monto_maximo' => 100_000_000,
        'porcentaje_retencion' => 4,
    ],
];
