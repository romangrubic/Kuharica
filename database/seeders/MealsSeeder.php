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
        $count = 200;
        $countCategory = Categories::all()->count();

//        Some meals don't have to have a category. So we make rand() to determine which one has category
        for ($i = 1; $i <= $count; $i++) {
            $random = rand(0,4);

            if ($random == 0) {
                DB::table('meals')->insert([
                    'category_id' => null,
                ]);
            }

//          Otherwise, set category
            $randomCategory = rand(1, $countCategory);
            DB::table('meals')->insert([
                'category_id' => $randomCategory,
            ]);
        }
    }
}
