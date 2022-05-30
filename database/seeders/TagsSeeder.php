<?php

/**
 * This file contains seeder class for tags table.
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * TagsSeeder is a seeder class for tags table.
 */
class TagsSeeder extends Seeder
{
    /**
     * Run the tags seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $count = 100;

        DB::beginTransaction();
        for ($i = 1; $i <= $count; $i++) {
            DB::table('tags')->insert([
                'slug' => 'tag-' . $i
            ]);
        }
        DB::commit();
    }
}
