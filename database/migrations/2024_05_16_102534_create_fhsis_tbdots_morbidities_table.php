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

            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->date('bdate');
            $table->string('age', 5);
            $table->string('sex', 1);
            $table->text('brgy');
            $table->text('source_of_patient');
            $table->string('ana_site');
            $table->string('reg_group');
            $table->string('bac_status');
            $table->string('xpert_result');
            $table->date('date_started_tx');
            $table->string('outcome')->nullable();
            
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
