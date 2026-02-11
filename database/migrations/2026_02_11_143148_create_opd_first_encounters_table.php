<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpdFirstEncountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opd_first_encounters', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')->constrained('syndromic_patients')->onDelete('cascade');

            $table->integer('year');
            $table->date('date_of_first_encounter')->nullable();
            $table->string('philhealth_pcu');
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
        Schema::dropIfExists('opd_first_encounters');
    }
}
