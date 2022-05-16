<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;


class CategoriesSeeder extends Seeder
{
    use HasFactory;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $count = 20;

        for ($i = 1; $i <= $count; $i++) {
            DB::table('categories')->insert([
                'slug' => 'category-' . $i
            ]);
        }
    }
}
