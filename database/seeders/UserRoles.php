<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserRole as UR;

class UserRoles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UR::create([
            'name' => 'Administrator',
        ], [
            'name' => 'User',
        ]);
    }
}
