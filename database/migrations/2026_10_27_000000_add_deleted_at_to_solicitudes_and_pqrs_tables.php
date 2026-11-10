<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Borrado lógico: al eliminar una solicitud o una PQRS desde el panel se conserva el registro,
     * su historial de auditoría y sus archivos (RF-08: trazabilidad ante reclamos).
     */
    public function up(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('pqrs', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('pqrs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
