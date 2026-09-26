<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pqrs', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('tipo');
            $table->string('estado')->default('recibida');

            $table->string('nombre');
            $table->string('documento');
            $table->string('email');
            $table->string('telefono')->nullable();

            $table->string('asunto');
            $table->text('descripcion');

            $table->text('respuesta')->nullable();
            $table->foreignId('respondido_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('respondido_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pqrs');
    }
};
