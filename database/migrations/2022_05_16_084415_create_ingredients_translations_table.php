<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientsTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredients_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredients_id')->references('id')->on('ingredients')->onDelete('cascade');
            $table->char('locale', 2)->index();
            $table->string('title');
            $table->unique(['ingredients_id','locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingredients_translations');
    }
}
