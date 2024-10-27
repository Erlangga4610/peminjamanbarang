<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $superAdmin = User::updateOrCreate(
            [
                'email' => 'gerlangga123@gmail.com'
            ],
            [
                'name' => 'gilang', 
                'password' => Hash::make('password')
            ],
        );

        $superAdmin->assignRole('super admin');
    

        //admin
       $admin = User::updateOrcreate(
            [
                'email' => 'gilange1232@gmail.com'
            ],
            [
                'name' => 'Agil',
                'password' => Hash::make('password'),
            ],
        );
        $admin->assignRole('admin');
        


    }
}
