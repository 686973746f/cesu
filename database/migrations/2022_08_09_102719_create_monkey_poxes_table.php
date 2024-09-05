<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonkeyPoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monkey_poxes', function (Blueprint $table) {
            $table->id();
            $table->date('date_investigation');
            $table->text('laboratory_id')->nullable();
            $table->text('epi_id')->nullable();
            $table->integer('enabled');
            $table->integer('match_casedef');

            $table->text('dru_name');
            $table->text('dru_region');
            $table->text('dru_province');
            $table->text('dru_muncity');
            $table->text('dru_street')->nullable();
            $table->text('dru_type');

            $table->string('patient_number')->nullable();
            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->date('bdate');
            $table->string('gender');
            $table->string('is_pregnant', 1);
            $table->integer('is_pregnant_weeks')->nullable();
            $table->text('other_medical_information')->nullable();
            $table->string('is_ip', 1);
            $table->string('is_ip_specify')->nullable();

            $table->string('nationality');

            $table->string('contact_number')->nullable();

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

            $table->text('perm_address_region_code');
            $table->text('perm_address_region_text');
            $table->text('perm_address_province_code');
            $table->text('perm_address_province_text');
            $table->text('perm_address_muncity_code');
            $table->text('perm_address_muncity_text');
            $table->text('perm_address_brgy_code');
            $table->text('perm_address_brgy_text');
            $table->text('perm_address_street')->nullable();
            $table->text('perm_address_houseno')->nullable();

            $table->string('occupation')->nullable();
            $table->string('workplace_name')->nullable();
            $table->text('workplace_address')->nullable();
            $table->string('workplace_contactnumber')->nullable();

            $table->string('informant_name')->nullable();
            $table->string('informant_relationship')->nullable();
            $table->string('informant_contactnumber')->nullable();
            //$table->date('date_reported');

            $table->date('date_admitted_seen_consulted')->nullable();
            $table->text('admission_er');
            $table->text('admission_ward');
            $table->text('admission_icu');
            //$table->date('date_discharge')->nullable();

            $table->text('ifhashistory_blooddonation_transfusion')->nullable();
            $table->text('ifhashistory_blooddonation_transfusion_place')->nullable();
            $table->date('ifhashistory_blooddonation_transfusion_date')->nullable();

            //$table->text('other_medicalinformation')->nullable();

            $table->date('date_onsetofillness');

            $table->string('have_cutaneous_rash', 1);
            $table->date('have_cutaneous_rash_date')->nullable();

            $table->string('have_fever', 1);
            $table->date('have_fever_date')->nullable();
            $table->integer('have_fever_days_duration')->nullable();

            $table->string('have_activedisease_lesion_samestate', 1);
            $table->string('have_activedisease_lesion_samesize', 1);
            $table->string('have_activedisease_lesion_deep', 1);
            $table->string('have_activedisease_develop_ulcers', 1);
            $table->text('have_activedisease_lesion_type')->nullable();
            $table->text('have_activedisease_lesion_localization')->nullable();
            $table->text('have_activedisease_lesion_localization_otherareas')->nullable();
            
            $table->text('symptoms_list')->nullable();
            $table->text('symptoms_lymphadenopathy_localization')->nullable();

            $table->string('history1_yn', 1);
            $table->text('history1_specify')->nullable();
            $table->date('history1_date_travel')->nullable();
            $table->string('history1_flightno')->nullable();
            $table->date('history1_date_arrival')->nullable();
            $table->text('history1_pointandexitentry')->nullable();

            $table->string('history2_yn', 1);
            $table->text('history2_specify')->nullable();
            $table->date('history2_date_travel')->nullable();
            $table->string('history2_flightno')->nullable();
            $table->date('history2_date_arrival')->nullable();
            $table->text('history2_pointandexitentry')->nullable();

            $table->string('history3_yn', 1);

            $table->string('history4_yn', 1);
            $table->text('history4_typeofanimal')->nullable();
            $table->date('history4_firstexposure')->nullable();
            $table->date('history4_lastexposure')->nullable();
            $table->text('history4_type')->nullable();
            $table->text('history4_type_others')->nullable();

            $table->text('history5_genderidentity');

            $table->string('history6_yn', 1);
            $table->string('history6_mtm', 1)->nullable();
            $table->integer('history6_mtm_nosp')->nullable();
            $table->string('history6_mtf', 1)->nullable();
            $table->integer('history6_mtf_nosp')->nullable();
            $table->string('history6_uknown', 1)->nullable();
            $table->integer('history6_uknown_nosp')->nullable();

            $table->string('history7_yn', 1);

            $table->string('history8_yn', 1);

            $table->string('history9_choice');
            $table->text('history9_choice_othercountry')->nullable();

            /*
            $table->string('test_npsops');
            $table->date('test_npsops_date_collected')->nullable();
            $table->text('test_npsops_laboratory')->nullable();
            $table->text('test_npsops_result')->nullable();
            $table->date('test_npsops_date_released')->nullable();
            $table->string('test_lesionfluid');
            $table->date('test_lesionfluid_date_collected')->nullable();
            $table->text('test_lesionfluid_laboratory')->nullable();
            $table->text('test_lesionfluid_result')->nullable();
            $table->date('test_lesionfluid_date_released')->nullable();
            $table->string('test_lesionroof');
            $table->date('test_lesionroof_date_collected')->nullable();
            $table->text('test_lesionroof_laboratory')->nullable();
            $table->text('test_lesionroof_result')->nullable();
            $table->date('test_lesionroof_date_released')->nullable();
            $table->string('test_lesioncrust');
            $table->date('test_lesioncrust_date_collected')->nullable();
            $table->text('test_lesioncrust_laboratory')->nullable();
            $table->text('test_lesioncrust_result')->nullable();
            $table->date('test_lesioncrust_date_released')->nullable();
            $table->string('test_serum');
            $table->date('test_serum_date_collected')->nullable();
            $table->text('test_serum_laboratory')->nullable();
            $table->text('test_serum_result')->nullable();
            $table->date('test_serum_date_released')->nullable();
            */

            $table->text('health_status');
            $table->date('health_status_date_discharged')->nullable();
            $table->text('health_status_final_diagnosis')->nullable();

            $table->text('outcome')->nullable();
            $table->text('outcome_unknown_type')->nullable();
            $table->date('outcome_date_recovered')->nullable();
            $table->date('outcome_date_died')->nullable();
            $table->text('outcome_causeofdeath')->nullable();
            $table->string('case_classification');

            $table->text('remarks')->nullable();
            $table->text('brgy_remarks')->nullable();

            $table->integer('age_years')->nullable();
            $table->integer('age_months')->nullable();
            $table->integer('age_days')->nullable();

            $table->integer('morbidity_month')->nullable();
            $table->integer('morbidity_week')->nullable();
            $table->integer('year')->nullable();

            //$table->foreignId('user_id')->constrained()->onDelete('cascade');
            //$table->foreignId('records_id')->constrained()->onDelete('cascade');

            $table->text('gps_x')->nullable();
            $table->text('gps_y')->nullable();

            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monkey_poxes');
    }
}
