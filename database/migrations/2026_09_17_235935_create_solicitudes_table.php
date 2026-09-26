<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('tramite_tipo_id')->constrained();

            $table->string('asociado_nombre');
            $table->string('asociado_documento');
            $table->string('asociado_email');
            $table->string('asociado_telefono')->nullable();

            $table->text('descripcion');
            $table->string('estado')->default('recibida');

            $table->foreignId('verificado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verificado_at')->nullable();

            $table->foreignId('asignado_a')->nullable()->constrained('users')->nullOnDelete();

            $table->text('justificacion')->nullable();
            $table->timestamp('finalizado_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
