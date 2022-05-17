<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = 50;

        DB::beginTransaction();
        for ($i = 1; $i <= $count; $i++) {
            DB::table('tags')->insert([
                'slug' => 'tag-' . $i
            ]);
        }
        DB::commit();
    }
}
