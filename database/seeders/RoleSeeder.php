<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //membuat
        $role = Role::updateOrCreate(['name' => 'super admin']);
        $role->givePermissionTo(Permission::all());

        $role = Role::updateOrCreate(['name' => 'admin']);


        //membuat permission
        $permissions = [
            'role-create',
            'role-edit',
            'role-delete',
            'role-view',
            'user-create',
            'user-edit',
            'user-delete',
            'user-view',
        ];

        foreach($permissions as $value) {
            Permission::updateOrCreate(
                [
                    'name' => $value
                ],
                [
                    'guard_name' => 'web'
                ]
            );
        }

        //give permission
        $role->givePermissionTo($permissions);
    }
}

//buat user
//buat role
//give role to user
//create permission 
//give permission to role
