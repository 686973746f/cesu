<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbtcVaccinationSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abtc_vaccination_sites', function (Blueprint $table) {
            $table->id();
            $table->string('site_name');
            $table->tinyInteger('enabled')->default(1);
            $table->text('referral_code');
            $table->text('sched_days')->nullable();
            $table->time('new_start')->nullable();
            $table->time('new_end')->nullable();
            $table->time('ff_start')->nullable();
            $table->time('ff_end')->nullable();
            $table->tinyInteger('new_and_ff_time_same')->default(0);
            $table->string('facility_type')->nullable();

            $table->string('ph_facility_name')->nullable();
            $table->string('ph_facility_code')->nullable();
            $table->string('ph_address_houseno')->nullable();
            $table->string('ph_doh_certificate')->nullable();
            $table->integer('ph_professional1_id')->nullable();
            $table->integer('ph_professional2_id')->nullable();
            $table->integer('ph_professional3_id')->nullable();
            $table->integer('ph_head_id')->nullable();
            $table->string('ph_accountant_name_position')->nullable();
            
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
        Schema::dropIfExists('abtc_vaccination_sites');
    }
}
