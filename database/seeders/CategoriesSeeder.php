<?php

/**
 * This file contains seeder class for categories table.
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

/**
 * CategoriesSeeder is a seeder class for categories table.
 */
class CategoriesSeeder extends Seeder
{
    use HasFactory;

    /**
     * Run the categories seeds.
     *
     * @param Faker $faker
     * @return void
     */
    public function run(Faker $faker): void
    {
        $count = 20;

        DB::beginTransaction();
        for ($i = 1; $i <= $count; $i++) {
            DB::table('categories')->insert([
                'slug' => 'category-' . $i
            ]);
        }
        DB::commit();
    }
}
