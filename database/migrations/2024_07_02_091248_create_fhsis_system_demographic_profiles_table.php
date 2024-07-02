<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFhsisSystemDemographicProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fhsis_system_demographic_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained('city')->onDelete('cascade');
            $table->string('for_year');
            $table->integer('total_brgy')->nullable();
            $table->integer('total_bhs')->nullable();
            $table->integer('total_mainhc')->nullable();
            $table->integer('total_cityhc')->nullable();
            $table->integer('total_ruralhc')->nullable();

            $table->integer('doctors_male')->nullable();
            $table->integer('doctors_female')->nullable();
            $table->integer('dentists_male')->nullable();
            $table->integer('dentists_female')->nullable();
            $table->integer('nurses_male')->nullable();
            $table->integer('nurses_female')->nullable();
            $table->integer('midwifes_male')->nullable();
            $table->integer('midwifes_female')->nullable();
            $table->integer('nutritionists_male')->nullable();
            $table->integer('nutritionists_female')->nullable();
            $table->integer('medtechs_male')->nullable();
            $table->integer('medtechs_female')->nullable();
            $table->integer('sanitary_eng_male')->nullable();
            $table->integer('sanitary_eng_female')->nullable();
            $table->integer('sanitary_ins_male')->nullable();
            $table->integer('sanitary_ins_female')->nullable();
            $table->integer('bhws_male')->nullable();
            $table->integer('bhws_female')->nullable();

            $table->integer('total_population')->nullable();
            $table->integer('total_household')->nullable();
            $table->integer('total_livebirths')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('fhsis_system_demographic_profiles');
    }
}
