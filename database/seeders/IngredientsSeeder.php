<?php

/**
 * This file contains seeder class for ingredients table.
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * IngredientsSeeder is a seeder class for ingredients table.
 */
class IngredientsSeeder extends Seeder
{
    /**
     * Run the ingredients seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $count = 100;

        DB::beginTransaction();
        for ($i = 1; $i <= $count; $i++) {
            DB::table('ingredients')->insert([
                'slug' => 'sastojak-' . $i
            ]);
        }
        DB::commit();
    }
}
