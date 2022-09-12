<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMorbidityWeeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('morbidity_weeks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('year');
            $table->integer('mw1')->nullable();
            $table->integer('mw2')->nullable();
            $table->integer('mw3')->nullable();
            $table->integer('mw4')->nullable();
            $table->integer('mw5')->nullable();
            $table->integer('mw6')->nullable();
            $table->integer('mw7')->nullable();
            $table->integer('mw8')->nullable();
            $table->integer('mw9')->nullable();
            $table->integer('mw10')->nullable();
            $table->integer('mw11')->nullable();
            $table->integer('mw12')->nullable();
            $table->integer('mw13')->nullable();
            $table->integer('mw14')->nullable();
            $table->integer('mw15')->nullable();
            $table->integer('mw16')->nullable();
            $table->integer('mw17')->nullable();
            $table->integer('mw18')->nullable();
            $table->integer('mw19')->nullable();
            $table->integer('mw20')->nullable();
            $table->integer('mw21')->nullable();
            $table->integer('mw22')->nullable();
            $table->integer('mw23')->nullable();
            $table->integer('mw24')->nullable();
            $table->integer('mw25')->nullable();
            $table->integer('mw26')->nullable();
            $table->integer('mw27')->nullable();
            $table->integer('mw28')->nullable();
            $table->integer('mw29')->nullable();
            $table->integer('mw30')->nullable();
            $table->integer('mw31')->nullable();
            $table->integer('mw32')->nullable();
            $table->integer('mw33')->nullable();
            $table->integer('mw34')->nullable();
            $table->integer('mw35')->nullable();
            $table->integer('mw36')->nullable();
            $table->integer('mw37')->nullable();
            $table->integer('mw38')->nullable();
            $table->integer('mw39')->nullable();
            $table->integer('mw40')->nullable();
            $table->integer('mw41')->nullable();
            $table->integer('mw42')->nullable();
            $table->integer('mw43')->nullable();
            $table->integer('mw44')->nullable();
            $table->integer('mw45')->nullable();
            $table->integer('mw46')->nullable();
            $table->integer('mw47')->nullable();
            $table->integer('mw48')->nullable();
            $table->integer('mw49')->nullable();
            $table->integer('mw50')->nullable();
            $table->integer('mw51')->nullable();
            $table->integer('mw52')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('morbidity_weeks');
    }
}
