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
            "password" => Hash::make("123"),
            "role" => "admin",
            "store_id" => $store,
            "store_role" => "admin"
        ]);
    }
}
