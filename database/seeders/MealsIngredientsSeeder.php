<?php

namespace Database\Seeders;

use App\Models\Ingredients;
use App\Models\Meals;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MealsIngredientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mealsArray = Meals::all()->toArray();
        $ingredientsArray = Ingredients::all()->toArray();

//        Storing ingredients ID in an array so that I can pull them in the foreach loop
        $ingredientsId = [];
        foreach ($ingredientsArray as $ingredient) {
            $ingredientsId[] = $ingredient['id'];
        }

        DB::beginTransaction();
        foreach ($mealsArray as $meal) {
//            Every meal has at least 1 ingredient (max 5)
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
