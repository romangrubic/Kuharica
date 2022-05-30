<?php

/**
 * This file contains seeder class for tags_translations table.
 */

namespace Database\Seeders;

use App\Models\{Languages,
    Tags};
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * TagsTranslationsSeeder is a seeder class for tags_translations table.
 */
class TagsTranslationsSeeder extends Seeder
{
    /**
     * Run the tags_translations seeds.
     *
     * @param Generator $faker
     * @return void
     */
    public function run(Generator $faker): void
    {
        $tagsArray = Tags::all()->toArray();
        $languageArray = Languages::all()->toArray();

        $languageCodes = [];
        foreach ($languageArray as $language) {
            $languageCodes[] = $language['code'];
        }

        DB::beginTransaction();
        foreach ($tagsArray as $tag) {
            foreach ($languageCodes as $code) {
                DB::table('tags_translations')->insert([
                    'tags_id' => $tag['id'],
                    'locale' => $code,
                    'title' => 'Jezik: ' . $code . ' - Naslov sastojka - ' . $tag['id'] . ' ' . $faker->text(100)
                ]);
            }
        }
        DB::commit();
    }
}
