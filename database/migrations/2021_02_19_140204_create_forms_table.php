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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('records_id')->constrained()->onDelete('cascade');
            $table->enum('isExported',[0,1])->default(0);
            
            $table->string('drunit');
            $table->string('drregion');
            $table->string('interviewerName');
            $table->string('interviewerMobile');
            $table->date('interviewDate');
            $table->string('informantName')->nullable();
            $table->string('informantRelationship')->nullable();
            $table->string('informantMobile')->nullable();
            $table->enum('pType', [1,2,3,4]);
            $table->text('pOthersRemarks')->nullable();
            $table->text('testingCat');

            $table->enum('havePreviousCovidConsultation', [0,1]);
            $table->date('dateOfFirstConsult')->nullable();
            $table->string('facilityNameOfFirstConsult')->nullable();
            $table->enum('admittedInHealthFacility', [0,1])->nullable();
            $table->date('dateOfAdmissionInHealthFacility')->nullable();
            $table->enum('admittedInMultipleHealthFacility', [0,1])->nullable();
            $table->string('facilitynameOfFirstAdmitted')->nullable();
            $table->string('fRegion')->nullable();
            $table->string('fRegionjson')->nullable();
            $table->string('fCity')->nullable();
            $table->string('fCityjson')->nullable();

            $table->tinyInteger('dispoType')->nullable();
            $table->string('dispoName')->nullable();
            $table->dateTime('dispoDate')->nullable();

            $table->string('healthStatus');
            $table->string('caseClassification');

            $table->enum('isHealthCareWorker', [0,1]);
            $table->string('healthCareCompanyName')->nullable();
            $table->string('healthCareCompanyLocation')->nullable();
            $table->enum('isOFW', [0,1]);
            $table->string('OFWCountyOfOrigin')->nullable();
            $table->enum('isFNT', [0,1]);
            $table->string('FNTCountryOfOrigin')->nullable();
            $table->enum('isLSI', [0,1]);
            $table->string('LSICity')->nullable();
            $table->string('LSICityjson')->nullable();
            $table->string('LSIProvince')->nullable();
            $table->string('LSIProvincejson')->nullable();
            $table->enum('isLivesOnClosedSettings', [0,1]);
            $table->string('institutionType')->nullable();
            $table->string('institutionName')->nullable();

            $table->string('oaddresslotbldg')->nullable();
            $table->string('oaddressstreet')->nullable();
            $table->string('oaddressscity')->nullable();
            $table->string('oaddresssprovince')->nullable();
            $table->string('oaddressscountry')->nullable();
            $table->string('placeofwork')->nullable();
            $table->string('employername')->nullable();
            $table->string('employercontactnumber')->nullable();

            $table->date('dateOnsetOfIllness')->nullable();
            $table->text('SAS')->nullable();
            $table->mediumInteger('SASFeverDeg')->nullable();
            $table->text('SASOtherRemarks')->nullable();
            $table->text('COMO');
            $table->text('COMOOtherRemarks')->nullable();
            $table->date('PregnantLMP')->nullable();
            $table->tinyInteger('PregnantHighRisk');

            $table->enum('diagWithSARI', [0,1]);
            $table->text('ImagingDone');
            $table->text('chestRDResult')->nullable();
            $table->text('chestRDOtherFindings')->nullable();
            $table->text('chestCTResult')->nullable();
            $table->text('chestCTOtherFindings')->nullable();
            $table->text('lungUSResult')->nullable();
            $table->text('lungUSOtherFindings')->nullable();

            $table->text('testsDoneList');
            $table->date('rtpcr_ops_date_collected')->nullable();
            $table->string('rtpcr_ops_laboratory')->nullable();
            $table->string('rtpcr_ops_results')->nullable();
            $table->date('rtpcr_ops_date_released')->nullable();

            $table->date('rtpcr_nps_date_collected')->nullable();
            $table->string('rtpcr_nps_laboratory')->nullable();
            $table->string('rtpcr_nps_results')->nullable();
            $table->date('rtpcr_nps_date_released')->nullable();

            $table->date('rtpcr_both_date_collected')->nullable();
            $table->string('rtpcr_both_laboratory')->nullable();
            $table->string('rtpcr_both_results')->nullable();
            $table->date('rtpcr_both_date_released')->nullable();

            $table->text('rtpcr_spec_type')->nullable();
            $table->date('rtpcr_spec_date_collected')->nullable();
            $table->string('rtpcr_spec_laboratory')->nullable();
            $table->string('rtpcr_spec_results')->nullable();
            $table->date('rtpcr_spec_date_released')->nullable();

            $table->date('antigen_date_collected')->nullable();
            $table->string('antigen_laboratory')->nullable();
            $table->string('antigen_results')->nullable();
            $table->date('antigen_date_released')->nullable();

            $table->date('antibody_date_collected')->nullable();
            $table->string('antibody_laboratory')->nullable();
            $table->string('antibody_results')->nullable();
            $table->date('antibody_date_released')->nullable();
            
            $table->string('others_specify')->nullable();
            $table->date('others_date_collected')->nullable();
            $table->string('others_laboratory')->nullable();
            $table->string('others_results')->nullable();
            $table->date('others_date_released')->nullable();

            $table->enum('testedPositiveUsingRTPCRBefore', [0,1]);
            $table->string('testedPositiveLab')->nullable();
            $table->date('testedPositiveSpecCollectedDate')->nullable();
            $table->mediumInteger('testedPositiveNumOfSwab');
            
            $table->text('outcomeCondition')->nullable();
            $table->date('outcomeRecovDate')->nullable();
            $table->date('outcomeDeathDate')->nullable();
            $table->text('deathImmeCause')->nullable();
            $table->text('deathAnteCause')->nullable();
            $table->text('deathUndeCause')->nullable();

            $table->enum('expoitem1', [1,2,3]);
            $table->date('expoDateLastCont')->nullable();
            $table->enum('expoitem2', [1,2,3]);
            $table->text('placevisited')->nullable();
            $table->text('vOpt1_details')->nullable();
            $table->date('vOpt1_date')->nullable();
            $table->text('vOpt2_details')->nullable();
            $table->date('vOpt2_date')->nullable();
            $table->text('vOpt3_details')->nullable();
            $table->date('vOpt3_date')->nullable();
            $table->text('vOpt4_details')->nullable();
            $table->date('vOpt4_date')->nullable();
            $table->text('vOpt5_details')->nullable();
            $table->date('vOpt5_date')->nullable();
            $table->text('vOpt6_details')->nullable();
            $table->date('vOpt6_date')->nullable();
            $table->text('vOpt7_details')->nullable();
            $table->date('vOpt7_date')->nullable();
            $table->text('vOpt8_details')->nullable();
            $table->date('vOpt8_date')->nullable();
            $table->text('vOpt9_details')->nullable();
            $table->date('vOpt9_date')->nullable();
            $table->text('vOpt10_details')->nullable();
            $table->date('vOpt10_date')->nullable();
            $table->text('vOpt11_details')->nullable();
            $table->date('vOpt11_date')->nullable();

            $table->enum('hasTravHistOtherCountries', [0,1]);
            $table->string('historyCountryOfExit')->nullable();
            $table->string('country_historyTypeOfTranspo')->nullable();
            $table->string('country_historyTranspoNo')->nullable();
            $table->date('country_historyTranspoDateOfDeparture')->nullable();
            $table->date('country_historyTranspoDateOfArrival')->nullable();

            $table->enum('hasTravHistLocal', [0,1]);
            $table->string('historyPlaceOfOrigin')->nullable();
            $table->string('local_historyTypeOfTranspo')->nullable();
            $table->string('local_historyTranspoNo')->nullable();
            $table->date('local_historyTranspoDateOfDeparture')->nullable();
            $table->date('local_historyTranspoDateOfArrival')->nullable();

            $table->text('contact1Name')->nullable();
            $table->text('contact1No', 11)->nullable();
            $table->text('contact2Name')->nullable();
            $table->text('contact2No', 11)->nullable();
            $table->text('contact3Name')->nullable();
            $table->text('contact3No', 11)->nullable();
            $table->text('contact4Name')->nullable();
            $table->text('contact4No', 11)->nullable();

            $table->text('addContName')->nullable();
            $table->text('addContNo')->nullable();
            $table->text('addContExpSet')->nullable();
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
