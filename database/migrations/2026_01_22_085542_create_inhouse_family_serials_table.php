<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInhouseFamilySerialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inhouse_family_serials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('syndromic_patients')->cascadeOnDelete();
            $table->string('inhouse_householdno');
            $table->string('inhouse_familyserialno')->nullable();

            $table->string('ics_householdno')->nullable();
            $table->string('ics_familyserialno')->nullable();
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
        Schema::dropIfExists('inhouse_family_serials');
    }
}
