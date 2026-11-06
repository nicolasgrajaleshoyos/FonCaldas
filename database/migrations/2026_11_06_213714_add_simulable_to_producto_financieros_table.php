<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('producto_financieros', function (Blueprint $table) {
            $table->boolean('simulable')
                ->default(true)
                ->after('activo');
        });
    }

    public function down(): void
    {
        Schema::table('producto_financieros', function (Blueprint $table) {
            $table->dropColumn('simulable');
        });
    }
};
