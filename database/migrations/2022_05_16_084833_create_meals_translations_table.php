<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealsTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meals_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meals_id')->references('id')->on('meals')->onDelete('cascade');
            $table->char('locale', 2)->index();
            $table->string('title');
            $table->longText('description');
            $table->unique(['meals_id','locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meals_translations');
    }
}
