<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->tinyInteger('paswab_enabled')->default(1);
            $table->tinyInteger('paswab_antigen_enabled')->default(1);
            $table->text('paswab_message_en')->nullable();
            $table->text('paswab_message_fil')->nullable();
            $table->time('oniStartTime_pm')->default('14:00');
            $table->time('oniStartTime_am')->default('09:00');
            $table->tinyInteger('lockencode_enabled')->default(1);
            $table->time('lockencode_start_time')->default('09:30');
            $table->time('lockencode_end_time')->default('09:30');
            $table->tinyInteger('lockencode_positive_enabled')->default(0);
            $table->time('lockencode_positive_start_time')->default('16:00');
            $table->time('lockencode_positive_end_time')->default('00:00');
            $table->time('encodeActiveCasesCutoff')->default('16:00');
            $table->text('listMobiles')->nullable();
            $table->text('listTelephone')->nullable();
            $table->text('listEmail')->nullable();
            $table->text('dilgCustomRespondentName')->nullable();
            $table->text('dilgCustomOfficeName')->nullable();
            $table->tinyInteger('unvaccinated_days_of_recovery')->default(10);
            $table->tinyInteger('partialvaccinated_days_of_recovery')->default(10);
            $table->tinyInteger('fullyvaccinated_days_of_recovery')->default(7);
            $table->tinyInteger('booster_days_of_recovery')->default(7);
            $table->tinyInteger('in_hospital_days_of_recovery')->default(21);
            $table->tinyInteger('severe_days_of_recovery')->default(21);
            $table->tinyInteger('paswab_auto_schedule_if_symptomatic')->default(0);
            $table->tinyInteger('cifpage_auto_schedule_if_symptomatic')->default(0);
            $table->string('system_type')->default('municipal'); //Options: Regional, Provincial, Municipal/City
            $table->text('default_dru_name');
            $table->text('default_dru_region');
            $table->text('default_dru_region_json');
            $table->text('default_dru_province')->nullable();
            $table->text('default_dru_province_json')->nullable();
            $table->text('default_dru_citymun')->nullable();
            $table->text('default_dru_citymun_json')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_settings');
    }
}
