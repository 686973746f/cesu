<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaSwabDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pa_swab_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->tinyInteger('status')->default(0);
            $table->string('majikCode');
            $table->string('pType');
            $table->tinyInteger('isForHospitalization');
            $table->date('interviewDate');

            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->date('bdate');
            $table->string('gender');
            $table->smallInteger('isPregnant');
            $table->date('ifPregnantLMP');
            $table->string('cs');
            $table->string('nationality');
            $table->string('mobile');
            $table->string('email')->nullable();

            $table->string('occupation')->nullable();
            $table->string('occupation_name')->nullable();
            $table->string('natureOfWork');
            $table->string('natureOfWorkIfOthers');
            $table->string('address_houseno');
            $table->string('address_street');
            $table->string('address_brgy');
            $table->string('address_city');
            $table->string('address_cityjson');
            $table->string('address_province');
            $table->string('address_provincejson');
            
            $table->text('symptoms')->nullable();
            $table->date('dateOnsetOfIllness')->nullable();

            $table->text('COMO');
            $table->string('xrayResult')->nullable();
            $table->date('xrayDate')->nullable();

            $table->date('expoDateLastCont')->nullable();

            $table->text('contact1Name')->nullable();
            $table->text('contact1No', 11)->nullable();
            $table->text('contact2Name')->nullable();
            $table->text('contact2No', 11)->nullable();
            $table->text('contact3Name')->nullable();
            $table->text('contact3No', 11)->nullable();
            $table->text('contact4Name')->nullable();
            $table->text('contact4No', 11)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pa_swab_details');
    }
}
