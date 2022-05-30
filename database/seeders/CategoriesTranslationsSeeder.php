<?php

namespace Database\Seeders;

use App\Models\{Categories,
    Languages};
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTranslationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
        $categoryArray = Categories::all()->toArray();
        $languageArray = Languages::all()->toArray();

//        For some reason, when inside second foreach loop $lang['code'] was throwing an error
//        So I made array outside that will prepare the same data
        $languageCodes = [];
        foreach ($languageArray as $language) {
            $languageCodes[] = $language['code'];
        }

//      Populating categories_translations with correct category ID and for each language in languages table
//      title field is being populated by faker
        DB::beginTransaction();
        foreach ($categoryArray as $category) {
            foreach ($languageCodes as $code) {
                DB::table('categories_translations')->insert([
                    'categories_id' => $category['id'],
                    'locale' => $code,
                    'title' => 'Jezik: ' . $code . ' - Naslov kategorije - ' . $category['id'] . ' ' . $faker->text(100)
                ]);
            }
        }
        DB::commit();
    }
}
