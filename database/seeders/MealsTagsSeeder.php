<?php

namespace Database\Seeders;

use App\Models\Tags;
use App\Models\Meals;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MealsTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mealsArray = Meals::all()->toArray();
        $tagsArray = Tags::all()->toArray();

//        Storing tags ID in an array so that I can pull them in the foreach loop
        $tagsId = [];
        foreach ($tagsArray as $tag) {
            $tagsId[] = $tag['id'];
        }

        DB::beginTransaction();
        foreach ($mealsArray as $meal) {
//            Every meal has at least 1 tag (max 4).
            $randomNumber = rand(1,4);

            for ($i = 1; $i <= $randomNumber; $i++) {
                DB::table('meals_tags')->insert([
                    'meals_id' => $meal['id'],
                    'tags_id' => $tagsId[array_rand($tagsId)],
                ]);
            }
        }
        DB::commit();
    }
}
