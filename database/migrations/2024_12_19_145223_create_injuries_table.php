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
            $table->date('bdate')->nullable();
            $table->integer('age_years')->nullable();
            $table->integer('age_months')->nullable();
            $table->integer('age_days')->nullable();

            $table->string('perm_streetpurok')->nullable();
            $table->foreignId('perm_brgy_code')->constrained('edcs_brgies')->onDelete('cascade');
            $table->string('tempaddress_sameasperm', 1);
            $table->string('temp_streetpurok')->nullable();
            $table->foreignId('temp_brgy_code')->constrained('edcs_brgies')->onDelete('cascade');

            $table->string('contact_no')->nullable();
            $table->string('contact_no2')->nullable();
            $table->string('philhealth')->nullable();

            //PRE-ADMISSION DATA
            $table->foreignId('injury_city_code')->constrained('edcs_cities')->onDelete('cascade');
            $table->foreignId('injury_brgy_code')->nullable()->constrained('edcs_brgies')->onDelete('cascade');

            $table->dateTime('injury_datetime')->nullable();
            $table->dateTime('consultation_datetime')->nullable();

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
            $table->text('others_site')->nullable();

            $table->string('bites_stings', 1);
            $table->string('bites_stings_specify')->nullable();
            $table->string('ext_burns', 1);
            $table->text('ext_burns_type')->nullable();
            $table->text('ext_burns_others_specify')->nullable();
            $table->string('chemical_substance', 1);
            $table->text('chemical_substance_specify')->nullable();
            $table->string('contact_sharpobject', 1);
            $table->text('contact_sharpobject_specify')->nullable();
            $table->string('drowning', 1);
            $table->string('drowning_type')->nullable();
            $table->text('drowning_other_specify')->nullable();
            $table->string('exposure_forcesofnature', 1);
            $table->string('fall', 1);
            $table->text('fall_specify')->nullable();
            $table->string('firecracker', 1);
            $table->text('firecracker_specify')->nullable();
            $table->string('sexual_assault', 1);
            $table->string('gunshot', 1);
            $table->string('gunshot_specifyweapon')->nullable();
            $table->string('hanging_strangulation', 1);
            $table->string('mauling_assault', 1);
            $table->string('transport_vehicular_accident', 1);
            $table->string('ext_others', 1);
            $table->text('ext_others_specify')->nullable();

            $table->string('vehicle_type')->nullable();
            $table->string('collision_type')->nullable();
            $table->string('patients_vehicle_involved')->nullable();
            $table->text('patients_vehicle_involved_others')->nullable();
            $table->string('other_vehicle_involved')->nullable();
            $table->text('other_vehicle_involved_others')->nullable();
            $table->text('patient_position')->nullable();
            $table->text('patient_position_others')->nullable();
            $table->string('placeof_occurrence')->nullable();
            $table->string('placeof_occurrence_workplace_specify')->nullable();
            $table->text('placeof_occurrence_others_specify')->nullable();
            $table->string('activitypatient_duringincident')->nullable();
            $table->text('act_others')->nullable();
            $table->text('otherrisk_factors')->nullable();
            $table->text('oth_factors_specify')->nullable();
            $table->text('safety')->nullable();
            $table->text('safety_others')->nullable();

            //HOSPITAL/FACILITY DATA
            $table->string('transfer_hospital', 1);
            $table->string('referred_hospital', 1);
            $table->string('orig_hospital')->nullable();
            $table->string('orig_physician')->nullable();
            $table->string('status_reachingfacility');
            $table->string('ifalive_type')->nullable();
            $table->string('modeof_transport');
            $table->text('modeof_transport_others')->nullable();
            $table->text('initial_impression')->nullable();
            $table->string('icd10_nature')->nullable();
            $table->string('icd10_external')->nullable();
            $table->string('disposition')->nullable();
            $table->string('disposition_transferred')->nullable();
            $table->string('disposition_others')->nullable();
            $table->string('outcome')->nullable();

            $table->text('inp_completefinal_diagnosis')->nullable();
            $table->string('inp_disposition')->nullable();
            $table->text('inp_disposition_others')->nullable();
            $table->string('inp_disposition_transferred')->nullable();
            $table->string('inp_outcome')->nullable();
            $table->string('inp_icd10_nature')->nullable();
            $table->string('inp_icd10_external')->nullable();

            $table->text('comments')->nullable();
            $table->string('qr');
            
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
