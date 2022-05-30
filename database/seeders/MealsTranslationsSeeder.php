<?php

/**
 * This file contains seeder class for meals_translations table.
 */

namespace Database\Seeders;

use App\Models\{Languages,
    Meals};
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * MealsTranslationsSeeder is a seeder class for meals_translations table.
 */
class MealsTranslationsSeeder extends Seeder
{
    /**
     * Run the meals_translations seeds.
     *
     * @param Generator $faker
     * @return void
     */
    public function run(Generator $faker): void
    {
        $mealsArray = Meals::all()->toArray();
        $languageArray = Languages::all()->toArray();

        $languageCodes = [];
        foreach ($languageArray as $language) {
            $languageCodes[] = $language['code'];
        }

        DB::beginTransaction();
        foreach ($mealsArray as $meal) {
            foreach ($languageCodes as $code) {
                DB::table('meals_translations')->insert([
                    'meals_id' => $meal['id'],
                    'locale' => $code,
                    'title' => 'Jezik: ' . $code . ' - Naslov jela - ' . $meal['id'] . ' ' . $faker->text(50),
                    'description' => 'Jezik: ' . $code . ' - Opis jela -' . $meal['id'] . ' ' . $faker->text(100)
                ]);
            }
        }
        DB::commit();
    }
}
