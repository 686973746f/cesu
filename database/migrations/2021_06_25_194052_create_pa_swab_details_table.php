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
            $table->string('suffix')->nullable();
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

            //Booster
            $table->date('vaccinationDate3')->nullable();
            $table->string('vaccinationName3')->nullable();
            $table->tinyInteger('vaccinationNoOfDose3')->nullable();
            $table->string('vaccinationFacility3')->nullable();
            $table->string('vaccinationRegion3')->nullable();
            $table->tinyInteger('haveAdverseEvents3')->nullable();

            //2nd Booster
            $table->date('vaccinationDate4')->nullable();
            $table->string('vaccinationName4')->nullable();
            $table->tinyInteger('vaccinationNoOfDose4')->nullable();
            $table->string('vaccinationFacility4')->nullable();
            $table->string('vaccinationRegion4')->nullable();
            $table->tinyInteger('haveAdverseEvents4')->nullable();
              
            $table->date('dateOnsetOfIllness')->nullable();
            $table->text('SAS')->nullable();
            $table->double('SASFeverDeg')->nullable();
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

            $table->text('placevisited')->nullable();

            $table->text('locName1')->nullable();
            $table->text('locAddress1')->nullable();
            $table->date('locDateFrom1')->nullable();
            $table->date('locDateTo1')->nullable();
            $table->enum('locWithOngoingCovid1', ["YES","NO","N/A"])->nullable();

            $table->text('locName2')->nullable();
            $table->text('locAddress2')->nullable();
            $table->date('locDateFrom2')->nullable();
            $table->date('locDateTo2')->nullable();
            $table->enum('locWithOngoingCovid2', ["YES","NO","N/A"])->nullable();
            
            $table->text('locName3')->nullable();
            $table->text('locAddress3')->nullable();
            $table->date('locDateFrom3')->nullable();
            $table->date('locDateTo3')->nullable();
            $table->enum('locWithOngoingCovid3', ["YES","NO","N/A"])->nullable();

            $table->text('locName4')->nullable();
            $table->text('locAddress4')->nullable();
            $table->date('locDateFrom4')->nullable();
            $table->date('locDateTo4')->nullable();
            $table->enum('locWithOngoingCovid4', ["YES","NO","N/A"])->nullable();

            $table->text('locName5')->nullable();
            $table->text('locAddress5')->nullable();
            $table->date('locDateFrom5')->nullable();
            $table->date('locDateTo5')->nullable();
            $table->enum('locWithOngoingCovid5', ["YES","NO","N/A"])->nullable();

            $table->text('locName6')->nullable();
            $table->text('locAddress6')->nullable();
            $table->date('locDateFrom6')->nullable();
            $table->date('locDateTo6')->nullable();
            $table->enum('locWithOngoingCovid6', ["YES","NO","N/A"])->nullable();

            $table->text('locName7')->nullable();
            $table->text('locAddress7')->nullable();
            $table->date('locDateFrom7')->nullable();
            $table->date('locDateTo7')->nullable();
            $table->enum('locWithOngoingCovid7', ["YES","NO","N/A"])->nullable();

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
