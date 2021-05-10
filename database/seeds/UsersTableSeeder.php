<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    private $seeds;

    public function __construct()
    {
        $this->seeds= [
            [
                'name' => "Admnistrator",
                'email' => "administrator@gmail.com",
                'password' => Hash::make('adminaichat'),
                'role' => 'admin',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
        ];
    }

    public function run()
    {
        foreach ($this->seeds as $key => $seed) {
            DB::table('users')->insert($seed);
        }
    }
}
