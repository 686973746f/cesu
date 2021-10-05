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
            $table->string('status')->default('pending');
            $table->tinyInteger('isNewRecord')->default(1);
            $table->foreignId('records_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('remarks')->nullable();
            $table->date('processedAt')->nullable();
            $table->string('linkCode')->nullable();
            $table->string('majikCode');
            $table->string('pType');
            $table->tinyInteger('isForHospitalization');
            $table->date('interviewDate');
            $table->tinyInteger('forAntigen');

            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->date('bdate');
            $table->string('gender');
            $table->smallInteger('isPregnant');
            $table->date('ifPregnantLMP')->nullable();
            $table->string('cs');
            $table->string('nationality');
            $table->string('mobile'); 
            $table->string('phoneno')->nullable();
            $table->string('email')->nullable();
            $table->string('philhealth')->nullable();
            $table->string('address_houseno');
            $table->string('address_street');
            $table->string('address_brgy');
            $table->string('address_city');
            $table->string('address_cityjson');
            $table->string('address_province');
            $table->string('address_provincejson');

            $table->string('occupation')->nullable();
            $table->string('occupation_name')->nullable();
            $table->string('natureOfWork')->nullable();
            $table->string('natureOfWorkIfOthers')->nullable();

            //Occupation Details
            $table->enum('worksInClosedSetting', ["YES","NO","UNKNOWN"])->default("NO");
            $table->string('occupation_lotbldg')->nullable();
            $table->string('occupation_street')->nullable();
            $table->string('occupation_brgy')->nullable();
            $table->string('occupation_city')->nullable();
            $table->string('occupation_cityjson')->nullable();
            $table->string('occupation_province')->nullable();
            $table->string('occupation_provincejson')->nullable();
            $table->string('occupation_mobile')->nullable();
            $table->string('occupation_email')->nullable();

            $table->date('vaccinationDate1')->nullable();
            $table->string('vaccinationName1')->nullable();
            $table->tinyInteger('vaccinationNoOfDose1')->nullable();
            $table->string('vaccinationFacility1')->nullable();
            $table->string('vaccinationRegion1')->nullable();
		    $table->tinyInteger('haveAdverseEvents1')->nullable();

            $table->date('vaccinationDate2')->nullable();
            $table->string('vaccinationName2')->nullable();
            $table->tinyInteger('vaccinationNoOfDose2')->nullable();
            $table->string('vaccinationFacility2')->nullable();
            $table->string('vaccinationRegion2')->nullable();
            $table->tinyInteger('haveAdverseEvents2')->nullable();
              
            $table->date('dateOnsetOfIllness')->nullable();
            $table->text('SAS')->nullable();
            $table->mediumInteger('SASFeverDeg')->nullable();
            $table->text('SASOtherRemarks')->nullable();

            $table->text('COMO');
            $table->text('COMOOtherRemarks')->nullable();

            $table->date('imagingDoneDate')->nullable();
            $table->string('imagingDone');
            $table->string('imagingResult')->nullable();
            $table->string('imagingOtherFindings')->nullable();

            $table->enum('expoitem1', [1,2,3]);
            $table->date('expoDateLastCont')->nullable();

            $table->text('contact1Name')->nullable();
            $table->text('contact1No', 11)->nullable();
            $table->text('contact2Name')->nullable();
            $table->text('contact2No', 11)->nullable();
            $table->text('contact3Name')->nullable();
            $table->text('contact3No', 11)->nullable();
            $table->text('contact4Name')->nullable();
            $table->text('contact4No', 11)->nullable();

            $table->string('patientmsg')->nullable();

            $table->ipAddress('senderIP');
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
