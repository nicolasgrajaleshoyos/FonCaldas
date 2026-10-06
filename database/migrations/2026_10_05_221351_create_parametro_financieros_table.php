<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parametro_financieros', function (Blueprint $table) {
            $table->id();

            $table->foreignId('producto_financiero_id')
                ->nullable()
                ->constrained('producto_financieros')
                ->nullOnDelete();

            $table->string('nombre');
            $table->string('codigo');
            $table->decimal('valor', 15, 6);
            $table->string('unidad')->nullable();

            $table->date('fecha_inicio_vigencia');
            $table->date('fecha_fin_vigencia')->nullable();

            $table->boolean('activo')->default(true);
            $table->text('descripcion')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametro_financieros');
    }
};
