<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFhsisSystemIcdTenCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fhsis_system_icd_ten_codes', function (Blueprint $table) {
            $table->id();
            $table->text('disease_name');
            $table->string('icd10_code');
            $table->string('icd10_description');
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
        Schema::dropIfExists('fhsis_system_icd_ten_codes');
    }
}
