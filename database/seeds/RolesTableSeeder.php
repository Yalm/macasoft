<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'Administrador',
            'slug' => 'admin',
            'description' => 'Acceso total'
        ]);

        DB::table('roles')->insert([
            'name' => 'Usuario',
            'slug' => 'user',
            'description' => 'Acceso a perfil'
        ]);

        DB::table('roles')->insert([
            'name' => 'Vendedor',
            'slug' => 'seller',
            'description' => 'Acceso a ventas'
        ]);
    }
}
