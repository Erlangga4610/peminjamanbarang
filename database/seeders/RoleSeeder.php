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
        Role::updateOrCreate([
            'name' => 'super admin',
            'guard_name' => 'web'
        ]);

        Role::updateOrCreate([
            'name' => 'user',
            'guard_name' => 'web'
        ]);
    }
}
