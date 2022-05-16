<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
//            Languages table first so DB can pull correct code for translations table
            LanguagesSeeder::class,
//            Adding categories and then categories_translations because of category->id
            CategoriesSeeder::class,
            CategoriesTranslationsSeeder::class,
//            Adding ingredients and then ingredients_translations because of ingredient->id
            IngredientsSeeder::class,
            IngredientsTranslationsSeeder::class,
//            Adding tags and then tags_translations because of tag->id
            TagsSeeder::class,
            TagsTranslationsSeeder::class,
//            Adding meals and then meals_translations because of meals->id
            MealsSeeder::class,
            MealsTranslationsSeeder::class,
//            Creating table for meals_ingredients with at least one ingredient for each meal
            MealsIngredientsSeeder::class,
//            Creating table for meals_tags with at least one tag for each meal
            MealsTagsSeeder::class,
        ]);
    }
}
