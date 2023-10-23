<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $taxes = [
            ["name" => "IVA (16)", "percentage" => 16],
            ["name" => "Capital Gains Tax", "percentage" => 15],
            ["name" => "Property Tax", "percentage" => 2.5],
            ["name" => "Personal Property Tax (Luxury Cars)", "percentage" => 5],
            ["name" => "Inheritance Tax", "percentage" => 10],
            ["name" => "Stamp Duty", "percentage" => 1],
            ["name" => "Special Tax (Alcohol)", "percentage" => 15],
            ["name" => "Customs Duties (Cars)", "percentage" => 12],
            ["name" => "Municipal Tax (Commercial Properties)", "percentage" => 3],
            ["name" => "Local Sales Tax", "percentage" => 7],
            ["name" => "Import Duty", "percentage" => 10],
            ["name" => "Luxury Tax", "percentage" => 4],
            ["name" => "Entertainment Tax", "percentage" => 12],
        ];

        foreach ($taxes as $tax) {
            DB::table("taxes")->insert($tax);
        }
    }
}
