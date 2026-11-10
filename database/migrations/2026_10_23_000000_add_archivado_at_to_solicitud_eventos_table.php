<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Los registros de auditoría no se borran: "limpiar" el panel solo los archiva. */
    public function up(): void
    {
        Schema::table('solicitud_eventos', function (Blueprint $table) {
            $table->timestamp('archivado_at')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::table('solicitud_eventos', function (Blueprint $table) {
            $table->dropColumn('archivado_at');
        });
    }
};
