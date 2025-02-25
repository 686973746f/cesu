<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFhsisSystemPopulationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fhsis_system_populations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brgy_id')->constrained('edcs_brgies')->onDelete('cascade');
            $table->string('year');
            $table->integer('population_m')->nullable();
            $table->integer('population_f')->nullable();
            $table->integer('population_estimate_total');
            $table->integer('population_actual_total')->nullable();
            $table->integer('household_estimate_total');
            $table->integer('household_actual_total')->nullable();

            $table->integer('END_POP_FIL');
            $table->integer('END_POP_MAL');
            $table->integer('END_POP_SCH');
            $table->integer('POP_UNDER1M');
            $table->integer('POP_UNDER1F');
            $table->integer('POP_0_6MOSM');
            $table->integer('POP_0_6MOSF');
            $table->integer('POP_0_59MOSM');
            $table->integer('POP_0_59MOSF');
            $table->integer('POP_6MOSM');
            $table->integer('POP_6MOSF');
            $table->integer('POP_6_11MOSM');
            $table->integer('POP_6_11MOSF');
            $table->integer('POP_12_23MOSM');
            $table->integer('POP_12_23MOSF');
            $table->integer('POP_12_59MOSM');
            $table->integer('POP_12_59MOSF');
            $table->integer('POP_0_1YRM');
            $table->integer('POP_0_1YRF');
            $table->integer('POP_0_14YRM');
            $table->integer('POP_0_14YRF');
            $table->integer('POP_1YRM');
            $table->integer('POP_1YRF');
            $table->integer('POP_2YRM');
            $table->integer('POP_2YRF');
            $table->integer('POP_2YRABOVEM');
            $table->integer('POP_2YRABOVEF');
            $table->integer('POP_3YRM');
            $table->integer('POP_3YRF');
            $table->integer('POP_4YRM');
            $table->integer('POP_4YRF');
            $table->integer('POP_1_4M');
            $table->integer('POP_1_4F');
            $table->integer('POP_5_9M');
            $table->integer('POP_5_9F');
            $table->integer('POP_5_65YRM');
            $table->integer('POP_5_65YRF');
            $table->integer('POP_5YRABOVEM');
            $table->integer('POP_5YRABOVEF');
            $table->integer('POP_6YRM');
            $table->integer('POP_6YRF');
            $table->integer('POP_9_14YRM');
            $table->integer('POP_9_14YRF');
            $table->integer('POP_10_14YRM');
            $table->integer('POP_10_14YRF');
            $table->integer('POP_10_19YRM');
            $table->integer('POP_10_19YRF');
            $table->integer('POP_10_49YRM');
            $table->integer('POP_10_49YRF');
            $table->integer('POP_12YRM');
            $table->integer('POP_12YRF');
            $table->integer('POP_15_19YRM');
            $table->integer('POP_15_19YRF');
            $table->integer('POP_15_49YRM');
            $table->integer('POP_15_49YRF');
            $table->integer('POP_20_49YRM');
            $table->integer('POP_20_49YRF');
            $table->integer('POP_20_59YRM');
            $table->integer('POP_20_59YRF');
            $table->integer('POP_20YRABOVEM');
            $table->integer('POP_20YRABOVEF');
            $table->integer('POP_25YRABOVEM');
            $table->integer('POP_25YRABOVEF');
            $table->integer('POP_60_65YRM');
            $table->integer('POP_60_65YRF');
            $table->integer('POP_60YRABOVEM');
            $table->integer('POP_60YRABOVEF');
            
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
        Schema::dropIfExists('fhsis_system_populations');
    }
}
