<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolesAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = ['admin', 'enseignant', 'parent', 'eleve'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@hnschool.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );
        $admin->syncRoles(['admin']);

        $teacher = User::updateOrCreate(
            ['email' => 'enseignant@hnschool.test'],
            [
                'name' => 'Enseignant Demo',
                'password' => Hash::make('password'),
            ]
        );
        $teacher->syncRoles(['enseignant']);

        $parent = User::updateOrCreate(
            ['email' => 'parent@hnschool.test'],
            [
                'name' => 'Parent Demo',
                'password' => Hash::make('password'),
            ]
        );
        $parent->syncRoles(['parent']);

        $eleve = User::updateOrCreate(
            ['email' => 'eleve@hnschool.test'],
            [
                'name' => 'Eleve Demo',
                'password' => Hash::make('password'),
            ]
        );
        $eleve->syncRoles(['eleve']);
    }
}
