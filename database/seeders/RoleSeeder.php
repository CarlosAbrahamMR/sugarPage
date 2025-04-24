<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = 'Administrador';
        $role->descripcion = 'Rol Administrador';
        $role->display_name = 'Fan';
        $role->save();

        $role = new Role();
        $role->name = 'Fan';
        $role->descripcion = 'Rol de fan';
        $role->display_name = 'Fan';
        $role->save();

        $role = new Role();
        $role->name = 'Sugar';
        $role->descripcion = 'Rol de sugar';
        $role->display_name = 'Sugar';
        $role->save();

        $user           = new User;
        $user->email    = 'admin@gmail.com';
        $user->name     = 'admin';
        $user->username = 'admin';
        $user->password = \Hash::make('password');
        $user->confirmation_code ='' ;
        $user->roles_id = 1;
        $user->save();
    }
}
