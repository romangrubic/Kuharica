<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = 100;

        DB::beginTransaction();
        for ($i = 1; $i <= $count; $i++) {
            DB::table('ingredients')->insert([
                'slug' => 'sastojak-' . $i
            ]);
        }
        DB::commit();
    }
}
