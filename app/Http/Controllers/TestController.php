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
//        Validate function for language, default 'en'
        $this->validateLanguage($request->input('lang'));

//        Getting parameters from Request GET only if they are not null
//        "lang" is already set and "with" is not going to Meals
        $parameters = [];

        $data = Meals::readMeals($parameters);

        return response()->json($data);

    }

    private function validateLanguage($language)
    {
//        Get all languages from DB to check if GET['lang'] exists or not!
        $codes = Languages::readCode();
//        Putting code values in array codeList to perform check
        $codeList = [];
        foreach ($codes as $code) {
            $codeList[] = $code->code;
        }
//        Check if $lang in array. If not, default to 'en' -> English
        if (!in_array($language, $codeList)) {
            $language = 'en';
        }
//        Setting locale to the $lang
        App::setLocale($language);
    }
}
