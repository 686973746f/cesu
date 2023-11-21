<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->tinyInteger('is_confidential')->default(0);
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('status');
            $table->foreignId('status_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('status_remarks')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->date('bdate');
            $table->string('gender');
            $table->smallInteger('isPregnant'); //only for female
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

            $table->text('address_lat')->nullable();
            $table->text('address_lng')->nullable();
            $table->text('address_region_psgc')->nullable();
            $table->text('address_province_psgc')->nullable();
            $table->text('address_muncity_psgc')->nullable();
            $table->text('address_brgy_psgc')->nullable();

            $table->tinyInteger('permaaddressDifferent');
            $table->string('permaaddress_houseno');
            $table->string('permaaddress_street');
            $table->string('permaaddress_brgy');
            $table->string('permaaddress_city');
            $table->string('permaaddress_cityjson');
            $table->string('permaaddress_province');
            $table->string('permaaddress_provincejson');
            $table->string('permamobile')->nullable();
            $table->string('permaphoneno')->nullable();
            $table->string('permaemail')->nullable();

            $table->text('perma_address_lat')->nullable();
            $table->text('perma_address_lng')->nullable();
            $table->text('perma_address_region_psgc')->nullable();
            $table->text('perma_address_province_psgc')->nullable();
            $table->text('perma_address_muncity_psgc')->nullable();
            $table->text('perma_address_brgy_psgc')->nullable();

            $table->smallInteger('hasOccupation');
            $table->string('occupation')->nullable();
            $table->string('natureOfWork')->nullable();
            $table->string('natureOfWorkIfOthers')->nullable();
            $table->enum('worksInClosedSetting', ["YES","NO","UNKNOWN"])->default("NO");
            $table->string('occupation_lotbldg')->nullable();
            $table->string('occupation_street')->nullable();
            $table->string('occupation_brgy')->nullable();
            $table->string('occupation_city')->nullable();
            $table->string('occupation_cityjson')->nullable();
            $table->string('occupation_province')->nullable();
            $table->string('occupation_provincejson')->nullable();
            $table->string('occupation_name')->nullable();
            $table->string('occupation_mobile')->nullable();
            $table->string('occupation_email')->nullable();

            $table->text('occupation_address_lat')->nullable();
            $table->text('occupation_address_lng')->nullable();
            $table->text('occupation_address_region_psgc')->nullable();
            $table->text('occupation_address_province_psgc')->nullable();
            $table->text('occupation_address_muncity_psgc')->nullable();
            $table->text('occupation_address_brgy_psgc')->nullable();

            $table->tinyInteger('if_vaccine_different_brand_dosage')->default(0);
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
            
            $table->text('remarks')->nullable();
            $table->text('sharedOnId')->nullable();

            $table->tinyInteger('isHCW')->default(0);
            $table->tinyInteger('isPriority')->default(0);
            $table->tinyInteger('isindg')->default(0);
            $table->string('indg_specify')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('records');
    }
}
