<?php

namespace Database\Seeders;

use App\Models\TypeOfServices;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class TypeOfServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        TypeOfServices::insert([
    [
        'service_name'=>'hanya cuci',
        'price' => 5000,
        'description' => 'service reguler'
    ],
    [
        'service_name'=>'Gosok dan cuci',
        'price' => 7000,
        'description' => 'service express'
    ],
    [
        'service_name'=>'cuci size besar',
        'price' => 6000,
        'description' => 'cuci premi'
    ]
    ]);
    }

    }

