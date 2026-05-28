<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cache dei permessi
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crea permessi
        Permission::create(['name' => 'manage teams']);
        Permission::create(['name' => 'manage projects']);
        Permission::create(['name' => 'manage tasks']);
        Permission::create(['name' => 'manage invoices']);
        Permission::create(['name' => 'view projects']);
        Permission::create(['name' => 'view invoices']);
        Permission::create(['name' => 'manage own tasks']);

        // Crea ruoli e assegna permessi
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $manager = Role::create(['name' => 'manager']);
        $manager->givePermissionTo([
            'manage projects',
            'manage tasks',
            'view invoices',
        ]);

        $employee = Role::create(['name' => 'employee']);
        $employee->givePermissionTo([
            'manage own tasks',
            'view projects',
        ]);
       

        $client = Role::create(['name' => 'client']);
        $client->givePermissionTo([
            'view projects',
            'view invoices',
        ]);
    
    }
}
