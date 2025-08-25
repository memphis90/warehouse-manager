<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crea i permessi
        $permissions = [
            'manage.users',
            'view.items',
            'create.items',
            'edit.items',
            'delete.items',
            'manage.items',
            'view.requests',
            'create.requests',
            'approve.requests',
            'reject.requests',
            'view.all.requests',
            'view.mine.requests',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crea il ruolo User
        $userRole = Role::where('name', 'user')->first() 
        ?? Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'create.requests',
            'view.mine.requests',
        ]);

        // Crea il ruolo Admin
        $adminRole = Role::where('name', 'admin')->first() 
        ?? Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all()); // Admin ha tutti i permessi

        // Super Admin (opzionale)
        $superAdminRole = Role::create(['name' => 'super-admin']);
        $superAdminRole->givePermissionTo(Permission::all());
    }
}