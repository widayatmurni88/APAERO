<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AirCrafEngineType as ACEngine;

class AirCrafEngineType extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ACEngine::create([
            'name' => 'Single Engine',
            'desc' => 'Mesinnya cuma satu yang didepan itu'
        ],[
            'name' => 'Multi Engine',
            'desc' => 'Mesinnya ada dua disayap biasanya'
        ]);
    }
}
