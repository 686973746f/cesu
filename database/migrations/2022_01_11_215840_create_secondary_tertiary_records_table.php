<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecondaryTertiaryRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secondary_tertiary_records', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('morbidityMonth');
            $table->date('dateReported');
            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('gender');
            $table->date('bdate')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('address_houseno')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_brgy')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_cityjson')->nullable();
            $table->string('address_province')->nullable();
            $table->string('address_provincejson')->nullable();
            $table->double('temperature')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('secondary_tertiary_records');
    }
}
