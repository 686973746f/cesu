<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHearsFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hears_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disaster_id')->constrained('evacuation_centers')->onDelete('cascade');
            $table->dateTime('date_posted');

            $table->text('brief_description');

            $table->integer('number_deaths');
            $table->integer('number_admitted');
            $table->integer('number_outpatient');
            $table->integer('number_missing');

            $table->string('has_popdisplacement', 1);
            $table->integer('number_dispfamily');
            $table->string('family_isestimate', 1);
            $table->integer('number_dispindividual');
            $table->string('individual_isestimate', 1);

            $table->integer('number_hospital_available');
            $table->integer('number_hospital_functional');
            $table->integer('number_rhu_available');
            $table->integer('number_rhu_functional');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hears_forms');
    }
}
