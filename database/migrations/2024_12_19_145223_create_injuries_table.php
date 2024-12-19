<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInjuriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('injuries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained('doh_facilities')->onDelete('cascade');
            $table->string('reported_by');
            $table->string('reporter_contactno');

            $table->string('patient_no')->nullable();
            $table->string('registry_no')->nullable();
            $table->string('case_no')->nullable();
            $table->string('patient_type');

            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();

            $table->string('sex', 1);
            $table->date('bdate');
            $table->integer('age_years');
            $table->integer('age_months');
            $table->integer('age_days');

            $table->string('perm_streetpurok')->nullable();
            $table->foreignId('perm_brgy_code')->constrained('edcs_brgies')->onDelete('cascade');
            $table->string('tempaddress_sameasperm', 1);
            $table->string('temp_streetpurok')->nullable();
            $table->foreignId('temp_brgy_code')->constrained('edcs_brgies')->onDelete('cascade');

            $table->string('contact_no')->nullable();
            $table->string('philhealth')->nullable();

            //PRE-ADMISSION DATA
            $table->foreignId('injury_city_code')->constrained('edcs_brgies')->onDelete('cascade');

            $table->dateTime('injury_datetime')->nullable();
            $table->string('consultation_datetime')->nullable();

            $table->string('injury_intent');
            $table->string('firstaid_given', 1);
            $table->string('firstaid_type')->nullable();
            $table->string('firstaid_bywho')->nullable();

            $table->string('multiple_injuries', 1);

            $table->string('abrasion', 1);
            $table->string('abrasion_site')->nullable();
            $table->string('avulsion', 1);
            $table->string('avulsion_site')->nullable();
            $table->string('burn', 1);
            $table->string('burn_degree')->nullable();
            $table->string('burn_site')->nullable();
            $table->string('concussion', 1);
            $table->string('concussion_site')->nullable();
            $table->string('contusion', 1);
            $table->string('contusion_site')->nullable();
            $table->string('fracture', 1);
            $table->string('fracture_open', 1)->nullable();
            $table->string('fracture_open_site')->nullable();
            $table->string('fracture_closed', 1)->nullable();
            $table->string('fracture_closed_site')->nullable();
            $table->string('open_wound', 1);
            $table->string('open_wound_site')->nullable();
            $table->string('traumatic_amputation', 1);
            $table->string('traumatic_amputation_site')->nullable();
            $table->string('others', 1);
            $table->string('others_site')->nullable();

            $table->string('bites_stings', 1);
            $table->string('bites_stings_specify')->nullable();
            $table->string('ext_burns', 1);
            $table->text('ext_burns_type')->nullable();
            $table->string('ext_burns_others_specify')->nullable();
            $table->string('chemical_substance', 1);
            $table->string('chemical_substance_specify')->nullable();
            $table->string('contact_sharpobject', 1);
            $table->string('contact_sharpobject_specify')->nullable();
            $table->string('drowning', 1);
            $table->string('drowning_type')->nullable();
            $table->string('drowning_other_specify')->nullable();
            $table->string('exposure_forcesofnature', 1);
            $table->string('fall', 1);
            $table->string('fall_specify')->nullable();
            $table->string('firecracker', 1);
            $table->string('firecracker_specify')->nullable();
            $table->string('sexual_assault', 1);
            $table->string('gunshot', 1);
            $table->string('gunshot_specifyweapon')->nullable();
            $table->string('hanging_strangulation', 1);
            $table->string('mauling_assault', 1);
            $table->string('transport_vehicular_accident', 1);
            $table->string('ext_others', 1);
            $table->string('ext_others_specify')->nullable();

            $table->string('vehicle_type')->nullable();
            $table->string('collision_type')->nullable();
            $table->text('patients_vehicle_involved')->nullable();
            $table->text('patients_vehicle_involved_others')->nullable();
            $table->text('other_vehicle_involved')->nullable();
            $table->text('other_vehicle_involved_others')->nullable();
            $table->string('patient_position')->nullable();
            $table->string('patient_position_others')->nullable();
            $table->string('placeof_occurrence')->nullable();
            $table->string('placeof_occurrence_workplace_specify')->nullable();
            $table->string('placeof_occurrence_others_specify')->nullable();
            $table->string('activitypatient_duringincident')->nullable();
            $table->string('activitypatient_duringincident_others')->nullable();
            $table->string('otherrisk_factors')->nullable();
            $table->string('otherrisk_factors_others')->nullable();
            $table->string('safety')->nullable();
            $table->string('safety_others')->nullable();

            //HOSPITAL/FACILITY DATA
            $table->string('transferred_fromanotherhospital', 1);
            $table->string('referredby_anotherhospital', 1);
            $table->string('originating_physician')->nullable();
            $table->string('status_reachingfacility');
            $table->string('status_ifalive_type')->nullable();
            $table->string('modeof_transport');
            $table->string('modeof_transport_others')->nullable();
            $table->text('initial_impression')->nullable();
            $table->string('icd10_nature')->nullable();
            $table->string('icd10_external')->nullable();
            $table->string('disposition')->nullable();
            $table->string('disposition_transferred')->nullable();
            $table->string('outcome')->nullable();

            $table->text('inp_completefinal_diagnosis')->nullable();
            $table->string('inp_disposition')->nullable();
            $table->string('inp_disposition_others')->nullable();
            $table->string('inp_disposition_transferred')->nullable();
            $table->string('inp_outcome')->nullable();
            $table->string('inp_icd10_nature')->nullable();
            $table->string('inp_icd10_external')->nullable();

            $table->text('comments')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');

            $table->integer('report_year');
            $table->integer('report_month');
            $table->integer('report_week');
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
        Schema::dropIfExists('injuries');
    }
}
