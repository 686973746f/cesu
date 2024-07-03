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
            $table->date('encode_date')->nullable();
            $table->foreignId('city_id')->constrained('city')->onDelete('cascade');
            $table->foreignId('brgy_id')->constrained('brgy')->onDelete('cascade');
            $table->string('for_year');
            $table->integer('total_brgy')->nullable();
            $table->integer('total_bhs')->nullable();
            $table->integer('total_mainhc')->nullable();
            $table->integer('total_cityhc')->nullable();
            $table->integer('total_ruralhc')->nullable();

            $table->integer('doctors_lgu')->nullable();
            $table->integer('doctors_doh')->nullable();
            $table->integer('dentists_lgu')->nullable();
            $table->integer('dentists_doh')->nullable();
            $table->integer('nurses_lgu')->nullable();
            $table->integer('nurses_doh')->nullable();
            $table->integer('midwifes_lgu')->nullable();
            $table->integer('midwifes_doh')->nullable();
            $table->integer('nutritionists_lgu')->nullable();
            $table->integer('nutritionists_doh')->nullable();
            $table->integer('medtechs_lgu')->nullable();
            $table->integer('medtechs_doh')->nullable();
            $table->integer('sanitary_eng_lgu')->nullable();
            $table->integer('sanitary_eng_doh')->nullable();
            $table->integer('sanitary_ins_lgu')->nullable();
            $table->integer('sanitary_ins_doh')->nullable();
            $table->integer('bhws_lgu')->nullable();
            $table->integer('bhws_doh')->nullable();

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
