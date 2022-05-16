<?php

namespace Database\Seeders;

use App\Models\Languages;
use App\Models\Tags;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagsTranslationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
        $tagsArray = Tags::all()->toArray();
        $languageArray = Languages::all()->toArray();

        $languageCodes = [];
        foreach ($languageArray as $language) {
            $languageCodes[] = $language['code'];
        }

        foreach ($tagsArray as $tag) {
            foreach ($languageCodes as $code) {
                DB::table('tags_translations')->insert([
                    'tags_id' => $tag['id'],
                    'locale' => $code,
                    'title' => 'Jezik: ' . $code . ' - Naslov sastojka - ' . $tag['id'] . ' ' . $faker->text(100)
                ]);
            }
        }
    }
}
