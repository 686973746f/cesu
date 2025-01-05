<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFwInjuriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fw_injuries', function (Blueprint $table) {
            $table->id();
            $table->string('oneiss_pno')->nullable();
            $table->string('oneiss_status')->nullable();
            $table->string('oneiss_dataentrystatus')->nullable();
            $table->string('oneiss_regno')->nullable();
            $table->string('oneiss_tempregno')->nullable();
            $table->string('oneiss_patfacilityno')->nullable();

            $table->dateTime('report_date');
            $table->string('facility_code');
            $table->string('account_type');
            $table->text('hospital_name');
            $table->string('reported_by');
            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->date('bdate');
            $table->string('gender');
            $table->string('is_4ps', 1)->default('N');

            $table->string('contact_number')->nullable();
            $table->string('contact_number2')->nullable();

            $table->text('address_region_code');
            $table->text('address_region_text');
            $table->text('address_province_code');
            $table->text('address_province_text');
            $table->text('address_muncity_code');
            $table->text('address_muncity_text');
            $table->text('address_brgy_code');
            $table->text('address_brgy_text');
            $table->text('address_street')->nullable();
            $table->text('address_houseno')->nullable();

            $table->dateTime('injury_date')->nullable();
            $table->dateTime('consultation_date')->nullable();

            $table->string('reffered_anotherhospital', 1)->default('N');
            $table->text('nameof_hospital', 1)->nullable();

            $table->string('place_of_occurrence')->nullable();
            $table->text('place_of_occurrence_others')->nullable();

            $table->string('injury_sameadd', 1)->nullable();

            $table->text('injury_address_region_code')->nullable();
            $table->text('injury_address_region_text')->nullable();
            $table->text('injury_address_province_code')->nullable();
            $table->text('injury_address_province_text')->nullable();
            $table->text('injury_address_muncity_code')->nullable();
            $table->text('injury_address_muncity_text')->nullable();
            $table->text('injury_address_brgy_code')->nullable();
            $table->text('injury_address_brgy_text')->nullable();
            $table->text('injury_address_street')->nullable();
            $table->text('injury_address_houseno')->nullable();

            $table->string('involvement_type')->nullable();
            $table->string('nature_injury')->nullable();
            $table->string('iffw_typeofinjury')->nullable();
            $table->text('complete_diagnosis')->nullable();

            $table->text('anatomical_location')->nullable();
            $table->text('firework_name')->nullable();
            $table->string('firework_illegal', 1)->nullable();
            $table->string('liquor_intoxication', 1)->default('N');

            $table->text('treatment_given')->nullable();
            $table->text('disposition_after_consultation')->nullable();
            $table->text('disposition_after_consultation_transferred_hospital')->nullable();

            $table->text('disposition_after_admission')->nullable();
            $table->text('disposition_after_admission_transferred_hospital')->nullable();

            $table->date('date_died')->nullable();
            $table->text('aware_healtheducation_list')->nullable();

            $table->integer('age_years')->nullable();
            $table->integer('age_months')->nullable();
            $table->integer('age_days')->nullable();

            $table->string('status')->default('ENABLED');
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('fw_injuries');
    }
}
