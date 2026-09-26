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

        if (!$username || !$hash) {
            return;
        }

        DB::table('users')->updateOrInsert(
            ['username' => $username],
            [
                'name' => 'Administrador FONCALDAS',
                'email' => $username . '@foncaldas.local',
                'password' => $hash,
                'is_super_admin' => true,
                'is_active' => true,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
