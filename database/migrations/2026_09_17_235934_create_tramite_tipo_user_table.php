<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tramite_tipo_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tramite_tipo_id')->constrained()->cascadeOnDelete();
            $table->unique(['user_id', 'tramite_tipo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tramite_tipo_user');
    }
};
