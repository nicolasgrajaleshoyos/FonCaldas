<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Migra el admin único basado en .env (ADMIN_USERNAME/ADMIN_PASSWORD_HASH) a un
     * usuario real "super admin". Usa DB::table (no el cast 'hashed' del modelo) para
     * insertar el hash bcrypt existente tal cual, sin volver a hashearlo, así las
     * credenciales actuales siguen funcionando.
     */
    public function run(): void
    {
        $username = config('admin.username');
        $hash = config('admin.password_hash');

        if (! $username || ! $hash) {
            return;
        }

        // created_at solo se fija al crear: una nueva ejecución del seeder no debe reescribir la fecha de alta.
        $existe = DB::table('users')->where('username', $username)->exists();

        DB::table('users')->updateOrInsert(
            ['username' => $username],
            [
                'name' => 'Administrador FONCALDAS',
                // Correo real para recibir los avisos; el dominio .local queda solo como respaldo si no se define.
                'email' => config('admin.email') ?: $username.'@foncaldas.local',
                'password' => $hash,
                'is_super_admin' => true,
                'is_active' => true,
                'updated_at' => now(),
            ] + ($existe ? [] : ['created_at' => now()])
        );
    }
}
