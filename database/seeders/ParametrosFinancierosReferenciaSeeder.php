<?php

namespace Database\Seeders;

use App\Models\ProductoFinanciero;
use App\Services\CargarProductosFinancieros;
use Illuminate\Database\Seeder;

class ParametrosFinancierosReferenciaSeeder extends Seeder
{
    public function run(CargarProductosFinancieros $cargaProductos): void
    {
        $conexion = (new ProductoFinanciero)->getConnection();
        $definiciones = config('parametros_financieros');
        $referencias = config('parametros_financieros_referencia');
        $inicio = today()->toDateString();

        [$creados, $existentes, $incompatibles] = $conexion->transaction(function () use (
            $cargaProductos, $conexion, $definiciones, $referencias, $inicio
        ): array {
            $cargaProductos->cargarFaltantes();

            $creados = 0;
            $existentes = 0;
            $incompatibles = [];

            foreach ($referencias as $codigoProducto => $parametros) {
                $consulta = ProductoFinanciero::where('codigo', $codigoProducto);
                if ($conexion->getDriverName() === 'sqlite') {
                    // SQLite obtiene el bloqueo de escritura antes de comprobar los parámetros.
                    $consulta->toBase()->update(['activo' => $conexion->raw('activo')]);
                }
                $producto = $consulta->lockForUpdate()->firstOrFail();
                $codigosExistentes = $producto->parametros()->lockForUpdate()->pluck('codigo');

                foreach ($parametros as $codigo => $valor) {
                    if ($codigosExistentes->containsStrict($codigo)) {
                        $existentes++;

                        continue;
                    }

                    if ($this->limiteIncompatible($producto, $codigo, $valor, $inicio)) {
                        $incompatibles[] = "{$producto->nombre} · {$definiciones[$codigo]['nombre']}";

                        continue;
                    }

                    $definicion = $definiciones[$codigo];
                    $producto->parametros()->create([
                        'codigo' => $codigo,
                        'nombre' => $definicion['nombre'],
                        'valor' => $valor,
                        'unidad' => $definicion['unidad_sugerida'],
                        'tipo_dato' => $definicion['tipo_dato'],
                        'categoria' => $definicion['categoria'],
                        'origen' => 'referencia',
                        'editable_admin' => true,
                        'fecha_inicio_vigencia' => $inicio,
                        'activo' => true,
                        'descripcion' => 'Referencia de desarrollo de la carga inicial.',
                    ]);
                    $creados++;
                }
            }

            return [$creados, $existentes, $incompatibles];
        }, attempts: 3);

        $this->command?->info("Parámetros de referencia creados: {$creados}. Parámetros existentes conservados: {$existentes}.");

        foreach ($incompatibles as $parametro) {
            $this->command?->warn("Omitido por incompatibilidad con un límite activo: {$parametro}.");
        }
    }

    private function limiteIncompatible(ProductoFinanciero $producto, string $codigo, int|float $valor, string $inicio): bool
    {
        $opuesto = match ($codigo) {
            'monto_minimo' => 'monto_maximo',
            'monto_maximo' => 'monto_minimo',
            'plazo_minimo' => 'plazo_maximo',
            'plazo_maximo' => 'plazo_minimo',
            default => null,
        };

        if ($opuesto === null) {
            return false;
        }

        return $producto->parametros()
            ->where('codigo', $opuesto)
            ->where('activo', true)
            ->where(fn ($consulta) => $consulta
                ->whereNull('fecha_fin_vigencia')
                ->orWhereDate('fecha_fin_vigencia', '>=', $inicio))
            ->where('valor', str_ends_with($codigo, '_minimo') ? '<' : '>', $valor)
            ->lockForUpdate()
            ->first(['id']) !== null;
    }
}
