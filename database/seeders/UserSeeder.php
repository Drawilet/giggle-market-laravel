<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $store = DB::table("stores")->insert([
            "name" => "Giggle Market",
        ]);

        DB::table("users")->insert([
            "name" => "John Doe",
            "email" => "johhdoe@walmart.com",
            "password" => "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi", // password
            "role" => "admin",
            "store_id" => $store,
            "store_role" => "admin"
        ]);
    }
}
