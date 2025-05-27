<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $student = Role::create(['name' => 'student']);
        // $instructor = Role::create(['name' => 'instructor']);
        // $admin = Role::create(['name' => 'admin']);
        $student = Role::create(['name' => 'student', 'guard_name' => 'api']);
        $instructor = Role::create(['name' => 'instructor', 'guard_name' => 'api']);
        $admin = Role::create(['name' => 'admin', 'guard_name' => 'api']);
    }
}
