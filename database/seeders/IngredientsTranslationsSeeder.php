<?php

namespace Database\Seeders;

use App\Models\Ingredients;
use App\Models\Languages;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientsTranslationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
        $ingredientsArray = Ingredients::all()->toArray();
        $languageArray = Languages::all()->toArray();

        $languageCodes = [];
        foreach ($languageArray as $language) {
            $languageCodes[] = $language['code'];
        }

        DB::beginTransaction();
        foreach ($ingredientsArray as $ingredient) {
            foreach ($languageCodes as $code) {
                DB::table('ingredients_translations')->insert([
                    'ingredients_id' => $ingredient['id'],
                    'locale' => $code,
                    'title' => 'Jezik: ' . $code . ' - Naslov sastojka - ' . $ingredient['id'] . ' ' . $faker->text(100)
                ]);
            }
        }
        DB::commit();
    }
}
