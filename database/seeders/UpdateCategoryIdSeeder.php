<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateCategoryIdSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Update products with null category_id to have a default category_id of 1
        DB::table('products')->whereNull('category_id')->update(['category_id' => 1]);
    }
}
