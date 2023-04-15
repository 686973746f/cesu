<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCovidVaccinePatientMasterlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covid_vaccine_patient_masterlists', function (Blueprint $table) {
            $table->id();

            $table->text('source_name')->nullable();
            $table->string('category');
            $table->string('comorbidity')->nullable();
            $table->string('unique_person_id');
            $table->string('pwd');
            $table->string('indigenous_member');
            $table->text('last_name')->nullable();
            $table->text('first_name')->nullable();
            $table->text('middle_name')->nullable();
            $table->string('suffix')->nullable();
            $table->string('contact_no')->nullable();
            $table->text('guardian_name')->nullable();
            $table->string('region');
            $table->string('province');
            $table->string('muni_city');
            $table->string('barangay');
            $table->string('sex');
            $table->date('birthdate');
            $table->string('deferral');
            $table->string('reason_for_deferral')->nullable();
            $table->date('vaccination_date');
            $table->text('vaccine_manufacturer_name');
            $table->text('batch_number');
            $table->text('lot_no');
            $table->text('bakuna_center_cbcr_id');
            $table->text('vaccinator_name')->nullable();
            $table->string('first_dose');
            $table->string('second_dose');
            $table->string('additional_booster_dose');
            $table->string('second_additional_booster_dose');
            $table->string('adverse_event');
            $table->text('adverse_event_condition')->nullable();
            $table->text('row_hash');
            
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
        Schema::dropIfExists('covid_vaccine_patient_masterlists');
    }
}
