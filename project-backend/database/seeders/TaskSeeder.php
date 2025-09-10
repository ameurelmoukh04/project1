<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tasks')->insert([
            'title' => 'Sample Task',
            'user_id' => 1,
        ]);
        DB::table('tasks')->insert([
            'title' => 'Important Task',
            'user_id' => 1,
        ]);
        DB::table('tasks')->insert([
            'title' => 'New Task',
            'user_id' => 3,
        ]);
    }
}
