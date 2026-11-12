<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            TramiteTipoSeeder::class,
            // Sin los productos, los simuladores y Parámetros financieros quedan vacíos en una instalación nueva.
            ProductosFinancierosSeeder::class,
        ]);
    }
}
