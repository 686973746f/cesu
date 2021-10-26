<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSelfReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('self_reports', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('status')->default('pending');
            $table->tinyInteger('isNewRecord')->default(1);
            $table->foreignId('records_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('patientmsg')->nullable();

            $table->string('drunit');
            $table->string('drregion');
            $table->string('drprovince');

            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('gender');
            $table->date('bdate');
            $table->string('cs');
            $table->string('nationality');
            $table->string('mobile'); 
            $table->string('phoneno')->nullable();
            $table->string('email')->nullable();
            $table->string('philhealth')->nullable();

            $table->smallInteger('isPregnant');
            $table->date('ifPregnantLMP')->nullable();
            
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

            $table->string('pType');

            $table->enum('isHealthCareWorker', [0,1]);
            $table->string('healthCareCompanyName')->nullable();
            $table->string('healthCareCompanyLocation')->nullable();
            $table->enum('isOFW', [0,1]);
            $table->string('OFWCountyOfOrigin')->nullable();
            $table->string('OFWPassportNo')->nullable();
            $table->enum('ofwType', [1,2])->nullable(); //new
            $table->enum('isFNT', [0,1]);
            $table->string('FNTCountryOfOrigin')->nullable();
            $table->string('FNTPassportNo')->nullable();
            $table->enum('isLSI', [0,1])->nullable();
            $table->string('LSICity')->nullable();
            $table->string('LSICityjson')->nullable();
            $table->string('LSIProvince')->nullable();
            $table->string('LSIProvincejson')->nullable();
            $table->enum('lsiType', [0,1])->nullable(); //new
            $table->enum('isLivesOnClosedSettings', [0,1]);
            $table->string('institutionType')->nullable();
            $table->string('institutionName')->nullable();

            $table->enum('havePreviousCovidConsultation', [0,1]);
            $table->date('dateOfFirstConsult')->nullable();
            $table->string('facilityNameOfFirstConsult')->nullable();

            $table->tinyInteger('dispoType')->nullable();
            $table->string('dispoName')->nullable();
            $table->dateTime('dispoDate')->nullable();

            $table->enum('testedPositiveUsingRTPCRBefore', [0,1]);
            $table->date('testedPositiveSpecCollectedDate')->nullable();
            $table->string('testedPositiveLab')->nullable();
            $table->mediumInteger('testedPositiveNumOfSwab');

            $table->date('testDateCollected1');
            $table->date('testDateReleased1')->nullable();
            $table->time('oniTimeCollected1')->nullable();
            $table->string('testLaboratory1')->nullable();
            $table->string('testType1');
            $table->string('testTypeAntigenRemarks1')->nullable();
            $table->string('antigenKit1')->nullable();
            $table->string('testTypeOtherRemarks1')->nullable();

            $table->date('vaccinationDate1')->nullable();
            $table->string('vaccinationName1')->nullable();
            $table->tinyInteger('vaccinationNoOfDose1')->nullable();
            $table->text('vaccinationFacility1')->nullable();
            $table->text('vaccinationRegion1')->nullable();
		    $table->tinyInteger('haveAdverseEvents1')->nullable();

            $table->date('vaccinationDate2')->nullable();
            $table->string('vaccinationName2')->nullable();
            $table->tinyInteger('vaccinationNoOfDose2')->nullable();
            $table->text('vaccinationFacility2')->nullable();
            $table->text('vaccinationRegion2')->nullable();
            $table->tinyInteger('haveAdverseEvents2')->nullable();

            $table->date('dateOnsetOfIllness')->nullable();
            $table->text('SAS')->nullable();
            $table->mediumInteger('SASFeverDeg')->nullable();
            $table->text('SASOtherRemarks')->nullable();
            $table->text('COMO');
            $table->text('COMOOtherRemarks')->nullable();

            $table->enum('diagWithSARI', [0,1]);
            $table->date('imagingDoneDate')->nullable();
            $table->string('imagingDone');
            $table->string('imagingResult')->nullable();
            $table->string('imagingOtherFindings')->nullable();

            $table->enum('expoitem1', [1,2,3])->nullable();
            $table->date('expoDateLastCont')->nullable();
            
            $table->enum('expoitem2', [0,1,2,3])->nullable();
            $table->string('intCountry')->nullable();
            $table->date('intDateFrom')->nullable();
            $table->date('intDateTo')->nullable();
            $table->enum('intWithOngoingCovid', ["YES","NO","N/A"])->nullable();
            $table->string('intVessel')->nullable();
            $table->string('intVesselNo')->nullable();
            $table->date('intDateDepart')->nullable();
            $table->date('intDateArrive')->nullable();

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

            $table->text('localVessel1')->nullable();
            $table->text('localVesselNo1')->nullable();
            $table->text('localOrigin1')->nullable();
            $table->date('localDateDepart1')->nullable();
            $table->text('localDest1')->nullable();
            $table->date('localDateArrive1')->nullable();

            $table->text('localVessel2')->nullable();
            $table->text('localVesselNo2')->nullable();
            $table->text('localOrigin2')->nullable();
            $table->date('localDateDepart2')->nullable();
            $table->text('localDest2')->nullable();
            $table->date('localDateArrive2')->nullable();

            $table->text('contact1Name')->nullable();
            $table->text('contact1No', 11)->nullable();
            $table->text('contact2Name')->nullable();
            $table->text('contact2No', 11)->nullable();
            $table->text('contact3Name')->nullable();
            $table->text('contact3No', 11)->nullable();
            $table->text('contact4Name')->nullable();
            $table->text('contact4No', 11)->nullable();

            $table->string('remarks')->nullable();

            $table->text('req_file')->nullable();
            $table->text('result_file');

            $table->ipAddress('senderIP');

            $table->text('magicURL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('self_reports');
    }
}
