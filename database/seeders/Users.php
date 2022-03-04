<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'administrator',
            'email' => 'admin@mail.com',
            'password' => bcrypt('123'),
        ]);
    }
}
