<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parametro_financieros', function (Blueprint $table) {

            $table->decimal('valor', 15, 6)
                ->nullable()
                ->change();

            $table->text('valor_texto')
                ->nullable()
                ->after('valor');

            $table->string('tipo_dato')
                ->default('decimal')
                ->after('valor_texto');

            $table->string('categoria')
                ->nullable()
                ->after('unidad');

            $table->string('origen')
                ->default('referencia')
                ->after('categoria');

            $table->boolean('editable_admin')
                ->default(true)
                ->after('origen');
        });
    }

    public function down(): void
    {
        // Los parámetros de texto o booleanos no tienen valor numérico y no existían antes de esta migración:
        // sin borrarlos, volver a exigir NOT NULL en valor falla a mitad del rollback.
        DB::table('parametro_financieros')->whereNull('valor')->delete();

        Schema::table('parametro_financieros', function (Blueprint $table) {
            $table->decimal('valor', 15, 6)
                ->nullable(false)
                ->change();

            $table->dropColumn([
                'valor_texto',
                'tipo_dato',
                'categoria',
                'origen',
                'editable_admin',
            ]);
        });
    }

};
