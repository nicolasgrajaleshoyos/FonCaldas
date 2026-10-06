<?php

namespace Database\Seeders;

use App\Models\ProductoFinanciero;
use Illuminate\Database\Seeder;

class ProductosFinancierosSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            [
                'nombre' => 'Crédito Avances',
                'codigo' => 'credito_avances',
                'tipo' => 'credito',
                'descripcion' => 'Línea de crédito para atender necesidades inmediatas del asociado.',
                'activo' => true,
            ],
            [
                'nombre' => 'Crédito Bonos',
                'codigo' => 'credito_bonos',
                'tipo' => 'credito',
                'descripcion' => 'Línea de crédito de corto plazo con pago en cuota única.',
                'activo' => true,
            ],
            [
                'nombre' => 'Crédito Bonificación',
                'codigo' => 'credito_bonificacion',
                'tipo' => 'credito',
                'descripcion' => 'Crédito asociado al valor de bonificación recibido por el asociado.',
                'activo' => true,
            ],
            [
                'nombre' => 'Crédito Cuota Única - junio',
                'codigo' => 'credito_cuota_unica_junio',
                'tipo' => 'credito',
                'descripcion' => 'Crédito con pago único asociado al período de junio.',
                'activo' => true,
            ],
            [
                'nombre' => 'Crédito Cuota Única - diciembre',
                'codigo' => 'credito_cuota_unica_diciembre',
                'tipo' => 'credito',
                'descripcion' => 'Crédito con pago único asociado al período de diciembre.',
                'activo' => true,
            ],
            [
                'nombre' => 'Crédito Libre Inversión',
                'codigo' => 'credito_libre_inversion',
                'tipo' => 'credito',
                'descripcion' => 'Línea de crédito para financiar bienes de consumo o servicios.',
                'activo' => true,
            ],
            [
                'nombre' => 'Crédito Compra de Cartera',
                'codigo' => 'credito_compra_cartera',
                'tipo' => 'credito',
                'descripcion' => 'Línea destinada a cancelar obligaciones del asociado con otras entidades.',
                'activo' => true,
            ],
            [
                'nombre' => 'Crédito Vehículo',
                'codigo' => 'credito_vehiculo',
                'tipo' => 'credito',
                'descripcion' => 'Línea para financiación de vehículo nuevo o usado.',
                'activo' => true,
            ],
            [
                'nombre' => 'Crédito Motocicleta',
                'codigo' => 'credito_motocicleta',
                'tipo' => 'credito',
                'descripcion' => 'Línea para financiación de motocicleta nueva.',
                'activo' => true,
            ],
            [
                'nombre' => 'Crédito Tecnología',
                'codigo' => 'credito_tecnologia',
                'tipo' => 'credito',
                'descripcion' => 'Línea destinada a la adquisición de productos tecnológicos.',
                'activo' => true,
            ],
            [
                'nombre' => 'Ahorro Ordinario',
                'codigo' => 'ahorro_ordinario',
                'tipo' => 'ahorro',
                'descripcion' => 'Producto de ahorro ordinario de FONCALDAS.',
                'activo' => true,
            ],
            [
                'nombre' => 'Ahorro CDAT',
                'codigo' => 'ahorro_cdat',
                'tipo' => 'ahorro',
                'descripcion' => 'Certificado de depósito de ahorro a término.',
                'activo' => true,
            ],
        ];

        foreach ($productos as $producto) {
            ProductoFinanciero::updateOrCreate(
                ['codigo' => $producto['codigo']],
                $producto
            );
        }
    }
}
