<?php

namespace Database\Seeders;

use App\Services\CargarProductosFinancieros;
use Illuminate\Database\Seeder;

class ProductosFinancierosSeeder extends Seeder
{
    public function run(): void
    {
        (new CargarProductosFinancieros)->cargarFaltantes();
    }
}
