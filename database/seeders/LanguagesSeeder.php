<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        If more languages are needed, just add another array in data.
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


////        Getting parameters from Request GET only if they are not null
////        "lang" is already set and "with" is not going to Meals
//$parameters = array_filter([
//    'per_page' => $request->input('per_page'),
//    'page' => $request->input('page'),
//    'category' => $request->input('category'),
//    'tags' => $request->input('tags'),
//    'diff_time' => $request->input('diff_time'),
//]);
//
//$data = Meals::readMeals($parameters);
//
//$with = $request->input('with');
////      If with is null, finish everything and return response to User
//if ($with == null){
//    return response()->json($data);
//}
////      Else, look for other stuff
//$withList = explode(',', $with);
////        dd($withList);
//if (in_array('category', $withList)) {
//    foreach ($data['data'] as $meal) {
//        if ($meal->category_id != null) {
//            $category = Categories::getCategory($meal->category_id);
//            unset($meal->category_id);
//            $meal->category = $category;
//        }
//    };
//}
//
//if (in_array('tags', $withList)) {
//    foreach ($data['data'] as $meal) {
//        $tags = MealsTags::searchByMealId($meal->id);
//        $tagList = [];
//        foreach ($tags as $tag) {
//            App::setLocale($lang);
//            $t = Tags::getTag($tag->tags_id);
//            $t->title = TagsTranslation::getTitle($t->id, App::getLocale())->title;
//            $tagList[] = $t;
//        }
//        $meal->tags = $tagList;
//    };
//}
//
//if (in_array('ingredients', $withList)) {
//    foreach ($data['data'] as $meal) {
//
//    };
//}
//
//$tags =Tags::all();
//
//
////      After with, send it!
