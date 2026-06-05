<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insertOrIgnore([
            ['id' => 1, 'name' => 'admin', 'display_name' => 'Administrator', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'faculty', 'display_name' => 'Faculty / Instructor', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'student', 'display_name' => 'Student', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
