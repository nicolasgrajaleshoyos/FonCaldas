<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Índices para las consultas que el sistema hace en cada página: listados del panel, historial y adjuntos
 * de una solicitud, reparto de carga entre responsables, PQRS, transparencia y eventos.
 * SQLite no crea índices para las claves foráneas (MySQL sí): por eso cada índice se crea solo si no existe ya
 * uno con esas mismas columnas, para no duplicarlos en otros motores. Solo agrega índices; no toca datos.
 */
return new class extends Migration
{
    /** @var array<string, list<list<string>>> tabla => índices (cada uno, lista de columnas) */
    private const INDICES = [
        // Panel de solicitudes: filtra por tipo de trámite y estado, ordena por fecha.
        'solicitudes' => [['tramite_tipo_id', 'estado'], ['asignado_a', 'estado'], ['created_at']],
        // Historial y adjuntos de cada solicitud.
        'solicitud_documentos' => [['solicitud_id', 'origen']],
        'solicitud_eventos' => [['solicitud_id'], ['user_id']],
        // Responsables de un tipo de trámite (el índice único existente empieza por user_id).
        'tramite_tipo_user' => [['tramite_tipo_id']],
        'pqrs' => [['estado'], ['created_at']],
        'documents' => [['category'], ['published_at']],
        'events' => [['event_date']],
        // Tasa vigente y control de vigencias superpuestas.
        'parametro_financieros' => [['producto_financiero_id', 'codigo', 'activo']],
    ];

    public function up(): void
    {
        foreach (self::INDICES as $tabla => $indices) {
            foreach ($indices as $columnas) {
                if (! Schema::hasIndex($tabla, $columnas)) {
                    Schema::table($tabla, fn (Blueprint $t) => $t->index($columnas));
                }
            }
        }

        // El nombre del trámite ya se valida como único en el formulario; el índice lo garantiza también ante
        // dos envíos simultáneos.
        if (! Schema::hasIndex('tramite_tipos', ['nombre'], 'unique')) {
            Schema::table('tramite_tipos', fn (Blueprint $t) => $t->unique('nombre'));
        }
    }

    public function down(): void
    {
        foreach (self::INDICES as $tabla => $indices) {
            foreach ($indices as $columnas) {
                $nombre = $tabla.'_'.implode('_', $columnas).'_index';
                if (Schema::hasIndex($tabla, $nombre)) {
                    Schema::table($tabla, fn (Blueprint $t) => $t->dropIndex($nombre));
                }
            }
        }

        if (Schema::hasIndex('tramite_tipos', 'tramite_tipos_nombre_unique')) {
            Schema::table('tramite_tipos', fn (Blueprint $t) => $t->dropUnique('tramite_tipos_nombre_unique'));
        }
    }
};
