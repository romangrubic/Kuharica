<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Languages;
use App\Models\Meals;
use App\Models\MealsTags;
use App\Models\TagsTranslation;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\Tags as Tags;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Traits\Relationship;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Builder;

class TestController extends Controller
{
    public function index(Request $request)
    {
//        Getting lang from GET
        $lang = $request->input('lang');
//        Checking if the GET lang exist in database
//        Ugly, but I got what I needed :/
        $codes = Languages::readCode();
        $codeList = [];
            foreach ($codes as $code) {
                    $codeList[] = $code->code;
            }
//        Checking if $lang in array. If not, default to 'en' -> english
        if (!in_array($lang, $codeList)) {
            $lang = 'en';
        }
//        Setting locale to the $lang
        App::setLocale($lang);
        dd(App::getLocale());
    }
}
