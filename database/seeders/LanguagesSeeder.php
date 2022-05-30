<?php

/**
 * This file contains seeder class for languages table.
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * LanguagesSeeder is a seeder class for languages table.
 */
class LanguagesSeeder extends Seeder
{
    /**
     * Run the languages seeds.
     *
     * @return void
     */
    public function run(): void
    {
        /**
         * If more languages are needed, just add another array in data.
         */
        $data = [
            ['language' => 'Hrvatski',
                'code' => 'hr'],
            ['language' => 'English',
                'code' => 'en'],
            ['language' => 'Deutsch',
                'code' => 'de'],
            ['language' => 'Italiana',
                'code' => 'it'],
            ['language' => 'Española',
                'code' => 'es']
        ];

        foreach ($data as $d) {
            DB::table('languages')->insert([
                'language' => $d['language'],
                'code' => $d['code'],
            ]);
        }
    }
}
