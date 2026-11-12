<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Toda tasa pertenece a un producto financiero, y un producto con tasas no se puede borrar (se conserva el historial
 * de vigencias). Antes: la columna aceptaba vacío y al borrar el producto sus tasas quedaban huérfanas.
 */
return new class extends Migration
{
    public function up(): void
    {
        $huerfanas = DB::table('parametro_financieros')->whereNull('producto_financiero_id')->count();
        if ($huerfanas > 0) {
            throw new RuntimeException("Hay {$huerfanas} tasa(s) sin producto financiero. Asígnales un producto o elimínalas antes de migrar.");
        }

        Schema::table('parametro_financieros', function (Blueprint $table) {
            $table->dropForeign(['producto_financiero_id']);
        });

        Schema::table('parametro_financieros', function (Blueprint $table) {
            $table->unsignedBigInteger('producto_financiero_id')->nullable(false)->change();
            $table->foreign('producto_financiero_id')->references('id')->on('producto_financieros')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('parametro_financieros', function (Blueprint $table) {
            $table->dropForeign(['producto_financiero_id']);
        });

        Schema::table('parametro_financieros', function (Blueprint $table) {
            $table->unsignedBigInteger('producto_financiero_id')->nullable()->change();
            $table->foreign('producto_financiero_id')->references('id')->on('producto_financieros')->nullOnDelete();
        });
    }
};
