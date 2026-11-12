<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * La auditoría guarda el nombre de quien hizo cada acción. Antes solo guardaba el id del usuario y, al eliminar
 * la cuenta de un funcionario, sus acciones pasaban a mostrarse como hechas por "Sistema".
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitud_eventos', function (Blueprint $table) {
            $table->string('actor_nombre')->nullable()->after('user_id');
        });

        // Completa los registros existentes con el nombre actual de cada usuario.
        DB::table('users')->orderBy('id')->each(function ($usuario) {
            DB::table('solicitud_eventos')->where('user_id', $usuario->id)->update(['actor_nombre' => $usuario->name]);
        });
    }

    public function down(): void
    {
        Schema::table('solicitud_eventos', function (Blueprint $table) {
            $table->dropColumn('actor_nombre');
        });
    }
};
