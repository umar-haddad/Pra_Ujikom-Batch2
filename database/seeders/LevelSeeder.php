<?php

namespace Database\Seeders;

use App\Models\Levels;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            [
                'name' => 'Administrator'
            ],
            [
                'name' => 'Operator'
            ],
            [
                'name' => 'Leader'
            ]
        ];

        Levels::insert($levels);
    }
}
