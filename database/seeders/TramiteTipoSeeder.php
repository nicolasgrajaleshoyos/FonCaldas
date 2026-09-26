<?php

namespace Database\Seeders;

use App\Models\TramiteTipo;
use Illuminate\Database\Seeder;

class TramiteTipoSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Créditos', 'slug' => 'creditos', 'es_certificacion' => false],
            ['nombre' => 'Ahorro', 'slug' => 'ahorro', 'es_certificacion' => false],
            ['nombre' => 'Bienestar', 'slug' => 'bienestar', 'es_certificacion' => false],
            ['nombre' => 'Convenios', 'slug' => 'convenios', 'es_certificacion' => false],
            ['nombre' => 'Certificación de afiliación', 'slug' => 'certificacion-afiliacion', 'es_certificacion' => true],
            ['nombre' => 'Certificación de aportes', 'slug' => 'certificacion-aportes', 'es_certificacion' => true],
            ['nombre' => 'Retiro de aportes', 'slug' => 'retiro-aportes', 'es_certificacion' => false],
            ['nombre' => 'Otro', 'slug' => 'otro', 'es_certificacion' => false],
        ];

        foreach ($tipos as $tipo) {
            TramiteTipo::updateOrCreate(['slug' => $tipo['slug']], $tipo);
        }
    }
}
