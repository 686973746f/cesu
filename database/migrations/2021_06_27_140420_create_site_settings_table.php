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
            $table->text('default_dru')->nullable();
            $table->text('default_dru_region')->nullable();
            $table->text('default_dru_province')->nullable();
            $table->time('encodeActiveCasesCutoff')->default('16:00');
            $table->text('listMobiles')->nullable();
            $table->text('listTelephone')->nullable();
            $table->text('listEmail')->nullable();
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
