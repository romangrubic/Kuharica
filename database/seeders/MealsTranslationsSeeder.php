<?php

namespace Database\Seeders;

use App\Models\Languages;
use App\Models\Meals;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MealsTranslationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
        $mealsArray = Meals::all()->toArray();
        $languageArray = Languages::all()->toArray();

        $languageCodes = [];
        foreach ($languageArray as $language) {
            $languageCodes[] = $language['code'];
        }

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
    }
}
