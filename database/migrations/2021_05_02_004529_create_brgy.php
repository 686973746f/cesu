<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrgy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brgy', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('city_id')->constrained('city')->onDelete('cascade');
            $table->string('brgyName');
            $table->tinyInteger('displayInList')->default(1);
            $table->integer('estimatedPopulation')->nullable();
            $table->text('dilgCustCode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brgy');
    }
}
