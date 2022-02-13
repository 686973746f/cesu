<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_cases', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('set_date');
            $table->text('type');
            $table->integer('total_active');
            $table->integer('total_recoveries');
            $table->integer('total_deaths');
            $table->integer('new_cases');
            $table->integer('late_cases');
            $table->integer('new_recoveries');
            $table->integer('late_recoveries');
            $table->integer('new_deaths');
            $table->integer('total_all_confirmed_cases');
            $table->integer('total_all_suspected_probable_cases');
            $table->integer('facility_one_count');
            $table->integer('facility_two_count');
            $table->integer('hq_count');
            $table->integer('hospital_count');
            $table->integer('active_asymptomatic_count');
            $table->integer('active_mild_with_comorbid_count');
            $table->integer('active_mild_without_comorbid_count');
            $table->integer('active_moderate_count');
            $table->integer('active_severe_count');
            $table->integer('active_critical_count');
            $table->integer('active_male_count');
            $table->integer('active_female_count');
            $table->integer('active_agegroup1_count');
            $table->integer('active_agegroup2_count');
            $table->integer('active_agegroup3_count');
            $table->integer('active_agegroup4_count');
            $table->integer('active_agegroup5_count');
            $table->integer('active_agegroup6_count');
            $table->integer('reinfection_active');
            $table->integer('reinfection_recovered');
            $table->integer('reinfection_deaths');
            $table->integer('reinfection_total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_cases');
    }
}
