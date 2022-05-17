<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MealsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        Create 500 meals. Change if you need more
        $count = 500;

        $categoryArray = Categories::all()->toArray();

//        Storing categories ID in an array so that I can pull them in the foreach loop
        $categoriesId = [];
        foreach ($categoryArray as $category) {
            $categoriesId[] = $category['id'];
        }

        DB::beginTransaction();
//        Some meals don't have to have a category. So we make rand() to determine which one has category
        for ($i = 1; $i <= $count; $i++) {
            $random = rand(0,4);

            if ($random == 0) {
                DB::table('meals')->insert([
                    'category_id' => null,
                ]);
            }

//          Otherwise, set category
            DB::table('meals')->insert([
                'category_id' => $categoriesId[array_rand($categoriesId)],
            ]);
        }
        DB::commit();
    }
}
