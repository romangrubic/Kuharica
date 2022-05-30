<?php

/**
 * This file contains seeder class for meals_ingredients table.
 */

namespace Database\Seeders;

use App\Models\{Ingredients,
    Meals};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * MealsIngredientsSeeder is a seeder class for meals_ingredients table.
 */
class MealsIngredientsSeeder extends Seeder
{
    /**
     * Run the meals_ingredients seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $mealsArray = Meals::all()->toArray();
        $ingredientsArray = Ingredients::all()->toArray();

        /**
         * Storing ingredients ID in an array so that I can pull them in the foreach loop.
         */
        $ingredientsId = [];
        foreach ($ingredientsArray as $ingredient) {
            $ingredientsId[] = $ingredient['id'];
        }

        DB::beginTransaction();
        foreach ($mealsArray as $meal) {
            $randomNumber = rand(1,5);

            for ($i = 1; $i <= $randomNumber; $i++) {
                DB::table('meals_ingredients')->insert([
                    'meals_id' => $meal['id'],
                    'ingredients_id' => $ingredientsId[array_rand($ingredientsId)],
                ]);
            }
        }
        DB::commit();
    }
}
