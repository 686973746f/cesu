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
            $table->string('enabled', 1);

            $table->string('patient_number')->nullable();
            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->date('bdate');
            $table->string('gender');

            $table->text('dru_name');
            $table->text('dru_address');
            $table->text('dru_adddress');

            $table->date('date_investigation');
            $table->date('date_reported');
            $table->text('epid_number')->nullable();
            
            $table->text('dru_name');
            $table->text('dru_region');
            $table->text('dru_province');
            $table->text('dru_muncity');
            $table->text('dru_street');

            $table->text('type');
            $table->text('laboratory_id')->nullable();

            $table->text('informant_name')->nullable();
            $table->text('informant_relationship')->nullable();
            $table->text('informant_contactnumber')->nullable();

            $table->date('date_admitted')->nullable();
            $table->text('admission_er');
            $table->text('admission_ward');
            $table->text('admission_icu');
            //$table->date('date_discharge')->nullable();

            $table->text('ifhashistory_blooddonation_transfusion')->nullable();
            $table->text('ifhashistory_blooddonation_transfusion_place')->nullable();
            $table->date('ifhashistory_blooddonation_transfusion_date')->nullable();

            $table->text('other_medicalinformation')->nullable();

            $table->date('date_onsetofillness');

            $table->string('have_cutaneous_rash');
            $table->date('have_cutaneous_rash_date')->nullable();

            $table->string('have_fever');
            $table->date('have_fever_date')->nullable();
            $table->integer('have_fever_days_duration')->nullable();

            $table->string('have_activedisease_lesion_samestate');
            $table->string('have_activedisease_lesion_samesize');
            $table->string('have_activedisease_lesion_deep');
            $table->string('have_activedisease_develop_ulcers');
            $table->text('have_activedisease_lesion_type')->nullable();
            $table->text('have_activedisease_lesion_localization')->nullable();
            $table->text('have_activedisease_lesion_localization_otherareas')->nullable();
            
            $table->text('symptoms_list')->nullable();
            $table->text('symptoms_lymphadenopathy_localization')->nullable();

            $table->string('history1_yn');
            $table->string('history1_specify')->nullable();
            $table->date('history1_date_travel')->nullable();
            $table->string('history1_flightno')->nullable();
            $table->date('history1_date_arrival')->nullable();
            $table->string('history1_pointandexitentry')->nullable();

            $table->string('history2_yn');
            $table->string('history2_specify')->nullable();
            $table->date('history2_date_travel')->nullable();
            $table->string('history2_flightno')->nullable();
            $table->date('history2_date_arrival')->nullable();
            $table->string('history2_pointandexitentry')->nullable();

            $table->string('history3_yn');

            $table->string('history4_yn');
            $table->text('history4_typeofanimal')->nullable();
            $table->date('history4_firstexposure')->nullable();
            $table->date('history4_lastexposure')->nullable();
            $table->text('history4_type')->nullable();
            $table->text('history4_type_others')->nullable();

            $table->text('history5_genderidentity');

            $table->string('history6_yn');
            $table->string('history6_mtm')->nullable();
            $table->integer('history6_mtm_nosp')->nullable();
            $table->string('history6_mtf')->nullable();
            $table->integer('history6_mtf_nosp')->nullable();
            $table->string('history6_uknown')->nullable();
            $table->integer('history6_uknown_nosp')->nullable();

            $table->string('history7_yn');

            $table->string('history8_yn');

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

            $table->integer('age_years')->nullable();
            $table->integer('age_months')->nullable();
            $table->integer('age_days')->nullable();

            $table->integer('morbidity_month')->nullable();
            $table->integer('morbidity_week')->nullable();
            $table->integer('year')->nullable();

            //$table->foreignId('user_id')->constrained()->onDelete('cascade');
            //$table->foreignId('records_id')->constrained()->onDelete('cascade');

            $table->string('gps_x')->nullable();
            $table->string('gps_x')->nullable();

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
