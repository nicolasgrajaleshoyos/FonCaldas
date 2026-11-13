<?php

return [

    'tasa_interes' => [
        'nombre' => 'Tasa de interés',
        'categoria' => 'tasa',
        'tipo_dato' => 'decimal',
        'unidad_sugerida' => '% M.V.',
    ],

    'tasa_interes_ea' => [
        'nombre' => 'Tasa de interés E.A.',
        'categoria' => 'tasa',
        'tipo_dato' => 'decimal',
        'unidad_sugerida' => '% E.A.',
    ],

    'tasa_interes_afiliacion_hasta_5' => [
        'nombre' => 'Tasa de interés - afiliación hasta 5 años',
        'categoria' => 'tasa',
        'tipo_dato' => 'decimal',
        'unidad_sugerida' => '% M.V.',
    ],

    'tasa_interes_afiliacion_5_15' => [
        'nombre' => 'Tasa de interés - afiliación de más de 5 hasta 15 años',
        'categoria' => 'tasa',
        'tipo_dato' => 'decimal',
        'unidad_sugerida' => '% M.V.',
    ],

    'tasa_interes_afiliacion_mas_15' => [
        'nombre' => 'Tasa de interés - afiliación de más de 15 años',
        'categoria' => 'tasa',
        'tipo_dato' => 'decimal',
        'unidad_sugerida' => '% M.V.',
    ],

    'tasa_interes_90_dias' => [
        'nombre' => 'Tasa de interés - 90 días',
        'categoria' => 'tasa',
        'tipo_dato' => 'decimal',
        'unidad_sugerida' => '% E.A.',
    ],

    'tasa_interes_180_dias' => [
        'nombre' => 'Tasa de interés - 180 días',
        'categoria' => 'tasa',
        'tipo_dato' => 'decimal',
        'unidad_sugerida' => '% E.A.',
    ],

    'tasa_interes_360_dias' => [
        'nombre' => 'Tasa de interés - 360 días',
        'categoria' => 'tasa',
        'tipo_dato' => 'decimal',
        'unidad_sugerida' => '% E.A.',
    ],

    'monto_minimo' => [
        'nombre' => 'Monto mínimo',
        'categoria' => 'monto',
        'tipo_dato' => 'decimal',
        'unidad_sugerida' => 'COP',
    ],

    'monto_maximo' => [
        'nombre' => 'Monto máximo',
        'categoria' => 'monto',
        'tipo_dato' => 'decimal',
        'unidad_sugerida' => 'COP',
    ],

    'plazo_minimo' => [
        'nombre' => 'Plazo mínimo',
        'categoria' => 'plazo',
        'tipo_dato' => 'entero',
        'unidad_sugerida' => 'meses',
    ],

    'plazo_maximo' => [
        'nombre' => 'Plazo máximo',
        'categoria' => 'plazo',
        'tipo_dato' => 'entero',
        'unidad_sugerida' => 'meses',
    ],

    'tipo_calculo' => [
        'nombre' => 'Tipo de cálculo',
        'categoria' => 'calculo',
        'tipo_dato' => 'texto',
        'unidad_sugerida' => null,
    ],

    'periodicidad_tasa' => [
        'nombre' => 'Periodicidad de la tasa',
        'categoria' => 'calculo',
        'tipo_dato' => 'texto',
        'unidad_sugerida' => null,
    ],

    'porcentaje_financiacion_nuevo' => [
        'nombre' => 'Financiación máxima para nuevo',
        'categoria' => 'condicion',
        'tipo_dato' => 'decimal',
        'unidad_sugerida' => '%',
    ],

    'porcentaje_financiacion_usado' => [
        'nombre' => 'Financiación máxima para usado',
        'categoria' => 'condicion',
        'tipo_dato' => 'decimal',
        'unidad_sugerida' => '%',
    ],

    'antiguedad_maxima_usado' => [
        'nombre' => 'Antigüedad máxima del usado',
        'categoria' => 'condicion',
        'tipo_dato' => 'entero',
        'unidad_sugerida' => 'años',
    ],

    'porcentaje_maximo_salario' => [
        'nombre' => 'Porcentaje máximo sobre salario',
        'categoria' => 'condicion',
        'tipo_dato' => 'decimal',
        'unidad_sugerida' => '%',
    ],

    'aporte_minimo' => [
        'nombre' => 'Aporte mínimo',
        'categoria' => 'monto',
        'tipo_dato' => 'decimal',
        'unidad_sugerida' => 'COP',
    ],

    'frecuencia_aporte' => [
        'nombre' => 'Frecuencia de aporte',
        'categoria' => 'condicion',
        'tipo_dato' => 'texto',
        'unidad_sugerida' => null,
    ],

    'porcentaje_retencion' => [
        'nombre' => 'Porcentaje de retención',
        'categoria' => 'retencion',
        'tipo_dato' => 'decimal',
        'unidad_sugerida' => '%',
    ],

    'base_calculo' => [
        'nombre' => 'Base de cálculo',
        'categoria' => 'calculo',
        'tipo_dato' => 'texto',
        'unidad_sugerida' => null,
    ],

    'capitalizacion' => [
        'nombre' => 'Capitalización',
        'categoria' => 'capitalizacion',
        'tipo_dato' => 'texto',
        'unidad_sugerida' => null,
    ],

    'base_anual_dias' => [
        'nombre' => 'Base anual de días',
        'categoria' => 'calculo',
        'tipo_dato' => 'entero',
        'unidad_sugerida' => 'días',
    ],

    'aportes_permitidos' => [
        'nombre' => 'Permite aportes adicionales',
        'categoria' => 'condicion',
        'tipo_dato' => 'booleano',
        'unidad_sugerida' => null,
    ],

    'retiros_permitidos' => [
        'nombre' => 'Permite retiros',
        'categoria' => 'condicion',
        'tipo_dato' => 'booleano',
        'unidad_sugerida' => null,
    ],

    'retiro_anticipado_permitido' => [
        'nombre' => 'Permite retiro anticipado',
        'categoria' => 'condicion',
        'tipo_dato' => 'booleano',
        'unidad_sugerida' => null,
    ],

    'renovacion_automatica' => [
        'nombre' => 'Renovación automática',
        'categoria' => 'condicion',
        'tipo_dato' => 'booleano',
        'unidad_sugerida' => null,
    ],

    'cotizacion_requerida' => [
        'nombre' => 'Cotización requerida',
        'categoria' => 'condicion',
        'tipo_dato' => 'booleano',
        'unidad_sugerida' => null,
    ],

];
