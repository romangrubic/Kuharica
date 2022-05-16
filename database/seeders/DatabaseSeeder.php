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
        ]);
    }
}
