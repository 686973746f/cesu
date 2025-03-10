<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->tinyInteger('isPriority')->default(0);
            $table->tinyInteger('reinfected')->default(0);
            $table->date('morbidityMonth');
            $table->dateTime('morbidityTime')->useCurrent();
            $table->string('majikCode')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('status');
            $table->timestamp('dateReported')->useCurrent();
            $table->foreignId('status_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('status_remarks')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('records_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('isExported')->default(0);
            $table->datetime('exportedDate')->nullable();
            $table->tinyInteger('isPresentOnSwabDay')->nullable();
            $table->tinyInteger('isForHospitalization');
            
            $table->string('drunit');
            $table->string('drregion')->nullable();
            $table->string('drprovince')->nullable();
            $table->string('interviewerName');
            $table->string('interviewerMobile'); 
            $table->date('interviewDate');
            $table->string('informantName')->nullable();
            $table->string('informantRelationship')->nullable();
            $table->string('informantMobile')->nullable();
            $table->text('existingCaseList'); // new
            $table->string('ecOthersRemarks')->nullable(); // new
            $table->string('pType');
            $table->tinyInteger('ccType')->nullable();
            $table->tinyInteger('is_primarycc')->default(0);
            $table->tinyInteger('is_secondarycc')->default(0);
            $table->tinyInteger('is_tertiarycc')->default(0);
            $table->date('is_primarycc_date')->nullable();
            $table->date('is_secondarycc_date')->nullable();
            $table->date('is_tertiarycc_date')->nullable();
            $table->dateTime('is_primarycc_date_set')->nullable();
            $table->dateTime('is_secondarycc_date_set')->nullable();
            $table->dateTime('is_tertiarycc_date_set')->nullable();
            $table->text('testingCat');

            $table->enum('havePreviousCovidConsultation', [0,1]);
            $table->date('dateOfFirstConsult')->nullable();
            $table->string('facilityNameOfFirstConsult')->nullable();

            $table->tinyInteger('dispoType')->nullable();
            $table->string('dispoName')->nullable();
            $table->dateTime('dispoDate')->nullable();

            $table->string('healthStatus');
            $table->string('caseClassification');
            $table->date('date_of_positive')->nullable();
            $table->string('confirmedVariantName')->nullable();

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
            $table->enum('isIndg', [0,1]); //new
            $table->string('indgSpecify')->nullable(); //new

            $table->date('dateOnsetOfIllness')->nullable();
            $table->text('SAS')->nullable();
            $table->double('SASFeverDeg')->nullable();
            $table->text('SASOtherRemarks')->nullable();
            $table->text('COMO');
            $table->text('COMOOtherRemarks')->nullable();
            $table->date('PregnantLMP')->nullable();
            $table->date('PregnantEDC')->nullable();
            $table->tinyInteger('PregnantHighRisk');

            $table->enum('diagWithSARI', [0,1]);
            $table->date('imagingDoneDate')->nullable();
            $table->string('imagingDone');
            $table->string('imagingResult')->nullable();
            $table->string('imagingOtherFindings')->nullable();

            $table->enum('testedPositiveUsingRTPCRBefore', [0,1]);
            $table->date('testedPositiveSpecCollectedDate')->nullable();
            $table->string('testedPositiveLab')->nullable();
            $table->mediumInteger('testedPositiveNumOfSwab');

            $table->date('testDateCollected1')->nullable();
            $table->date('testDateReleased1')->nullable();
            $table->time('oniTimeCollected1')->nullable();
            $table->string('testLaboratory1')->nullable();
            $table->string('testType1')->nullable();
            $table->string('testTypeAntigenRemarks1')->nullable();
            $table->string('antigenKit1')->nullable();
            
            $table->foreignId('antigen_id1')->nullable()->constrained('antigens')->onDelete('cascade');
            $table->text('antigenLotNo1')->nullable();
            
            $table->string('testTypeOtherRemarks1')->nullable();
            $table->string('testResult1')->nullable();
            $table->string('testResultOtherRemarks1')->nullable();

            $table->date('testDateCollected2')->nullable();
            $table->time('oniTimeCollected2')->nullable();
            $table->date('testDateReleased2')->nullable();
            $table->string('testLaboratory2')->nullable();
            $table->string('testType2')->nullable();
            $table->string('testTypeAntigenRemarks2')->nullable();
            $table->string('antigenKit2')->nullable();
            
            $table->foreignId('antigen_id2')->nullable()->constrained('antigens')->onDelete('cascade');
            $table->text('antigenLotNo2')->nullable();

            $table->string('testTypeOtherRemarks2')->nullable();
            $table->string('testResult2')->nullable();
            $table->string('testResultOtherRemarks2')->nullable();

            $table->text('outcomeCondition')->nullable();
            $table->date('outcomeRecovDate')->nullable();
            $table->date('outcomeDeathDate')->nullable();
            $table->text('deathImmeCause')->nullable();
            $table->text('deathAnteCause')->nullable();
            $table->text('deathUndeCause')->nullable();
            $table->text('contriCondi')->nullable();

            $table->enum('expoitem1', [1,2,3]);
            $table->date('expoDateLastCont')->nullable();
            
            $table->enum('expoitem2', [0,1,2,3]);
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
            $table->text('facility_remarks')->nullable();

            $table->text('ccid_list')->nullable();

            $table->tinyInteger('is_disobedient')->default(0);
            $table->text('disobedient_remarks')->nullable();

            $table->text('antigenqr')->nullable();
            $table->tinyInteger('sent')->default(0);

            $table->integer('age_years')->nullable();
            $table->integer('age_months')->nullable();
            $table->integer('age_days')->nullable();

            $table->tinyInteger('from_tkc')->default(0);
            $table->text('tkc_id')->nullable();
            $table->text('tkc_lgu_id')->nullable();
            $table->string('tkc_casetracking_status', 1)->nullable();
            $table->text('tkc_created_by')->nullable();
            
            $table->dateTime('tkc_date_verified')->nullable();
            $table->text('tkc_verified_assessment')->nullable();

            $table->text('tkc_nonhealth_dru')->nullable();
            $table->text('tkc_sentinel_reporting_unit')->nullable();
            $table->text('tkc_outcome')->nullable();
            $table->string('system_isverified', 1)->nullable();
            $table->tinyInteger('notify_email_sent')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forms');
    }
}
