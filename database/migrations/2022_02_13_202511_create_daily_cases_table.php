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
            $table->integer('total_active')->default(0);
            $table->integer('total_active_unvaccinated')->default(0);
            $table->integer('total_active_halfvax')->default(0);
            $table->integer('total_active_fullvax')->default(0);
            $table->integer('total_active_booster')->default(0);
            $table->integer('total_recoveries')->default(0);
            $table->integer('total_recoveries_unvaccinated')->default(0);
            $table->integer('total_recoveries_halfvax')->default(0);
            $table->integer('total_recoveries_fullvax')->default(0);
            $table->integer('total_recoveries_booster')->default(0);
            $table->integer('total_deaths')->default(0);
            $table->integer('total_deaths_unvaccinated')->default(0);
            $table->integer('total_deaths_halfvax')->default(0);
            $table->integer('total_deaths_fullvax')->default(0);
            $table->integer('total_deaths_booster')->default(0);
            $table->integer('new_cases')->default(0);
            $table->integer('new_cases_unvaccinated')->default(0);
            $table->integer('new_cases_halfvax')->default(0);
            $table->integer('new_cases_fullvax')->default(0);
            $table->integer('new_cases_booster')->default(0);
            $table->integer('late_cases')->default(0);
            $table->integer('late_cases_unvaccinated')->default(0);
            $table->integer('late_cases_halfvax')->default(0);
            $table->integer('late_cases_fullvax')->default(0);
            $table->integer('late_cases_booster')->default(0);
            $table->integer('new_recoveries')->default(0);
            $table->integer('new_recoveries_unvaccinated')->default(0);
            $table->integer('new_recoveries_halfvax')->default(0);
            $table->integer('new_recoveries_fullvax')->default(0);
            $table->integer('new_recoveries_booster')->default(0);
            $table->integer('late_recoveries')->default(0);
            $table->integer('late_recoveries_unvaccinated')->default(0);
            $table->integer('late_recoveries_halfvax')->default(0);
            $table->integer('late_recoveries_fullvax')->default(0);
            $table->integer('late_recoveries_booster')->default(0);
            $table->integer('new_deaths')->default(0);
            $table->integer('new_deaths_unvaccinated')->default(0);
            $table->integer('new_deaths_halfvax')->default(0);
            $table->integer('new_deaths_fullvax')->default(0);
            $table->integer('new_deaths_booster')->default(0);
            $table->integer('total_all_confirmed_cases')->default(0);
            $table->integer('total_all_suspected_probable_cases')->default(0);
            $table->integer('facility_one_count')->default(0);
            $table->integer('facility_two_count')->default(0);
            $table->integer('hq_count')->default(0);
            $table->integer('hospital_count')->default(0);
            $table->integer('active_asymptomatic_count')->default(0);
            $table->integer('active_mild_with_comorbid_count')->default(0);
            $table->integer('active_mild_without_comorbid_count')->default(0);
            $table->integer('active_moderate_count')->default(0);
            $table->integer('active_severe_count')->default(0);
            $table->integer('active_critical_count')->default(0);
            $table->integer('active_male_count')->default(0);
            $table->integer('active_female_count')->default(0);
            $table->integer('active_agegroup1_count')->default(0);
            $table->integer('active_agegroup2_count')->default(0);
            $table->integer('active_agegroup3_count')->default(0);
            $table->integer('active_agegroup4_count')->default(0);
            $table->integer('active_agegroup5_count')->default(0);
            $table->integer('active_agegroup6_count')->default(0);
            $table->integer('reinfection_active')->default(0);
            $table->integer('reinfection_recovered')->default(0);
            $table->integer('reinfection_deaths')->default(0);
            $table->integer('reinfection_total')->default(0);
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
