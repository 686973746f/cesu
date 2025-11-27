<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFhsisTbdotsMorbiditiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fhsis_tbdots_morbidities', function (Blueprint $table) {
            $table->id();

            $table->string('type')->nullable();
            $table->string('validation_status')->nullable();
            $table->date('screening_date')->nullable();
            $table->date('diagnosis_date')->nullable();
            $table->date('notification_date')->nullable();
            $table->string('case_number')->nullable();
            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->date('bdate');
            $table->integer('age');
            $table->integer('age_months');
            $table->integer('age_days');
            $table->string('sex', 1);
            $table->text('brgy');
            $table->text('source_of_patient');
            $table->string('ana_site');
            $table->string('reg_group');
            $table->string('bac_status');
            $table->string('xpert_result');
            $table->date('rdt_release_date')->nullable();
            $table->date('date_started_tx');
            $table->string('outcome')->nullable();
            $table->date('date_of_outcomestatus')->nullable();
            $table->dateTime('datetime_record_was_created')->nullable();
            
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
        Schema::dropIfExists('fhsis_tbdots_morbidities');
    }
}
