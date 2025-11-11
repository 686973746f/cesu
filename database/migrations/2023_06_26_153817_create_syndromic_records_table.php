<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyndromicRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syndromic_records', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('hospital_completion')->nullable();
            $table->foreignId('syndromic_patient_id')->constrained('syndromic_patients')->onDelete('cascade');
            
            $table->text('opdno');
            $table->string('facility_controlnumber')->nullable();
            $table->integer('line_number')->nullable();
            $table->date('last_checkup_date')->nullable();
            $table->dateTime('consultation_date');
            $table->text('nature_of_visit')->nullable();
            $table->text('consultation_type')->nullable();
            $table->string('checkup_type')->nullable();

            $table->string('hosp_identifier')->nullable();
            
            $table->string('chief_complain');
            $table->date('date_general_onset')->nullable();
            $table->tinyInteger('rx_outsidecho')->default(0);
            $table->text('outsidecho_name')->nullable();

            $table->tinyInteger('is_pregnant')->default(0);
            $table->date('lmp')->nullable();
            $table->date('edc')->nullable();

            $table->string('temperature')->nullable();
            $table->string('bloodpressure')->nullable();
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->string('respiratoryrate')->nullable();
            $table->string('pulserate')->nullable();
            $table->string('saturationperioxigen')->nullable();
            $table->string('o2sat')->nullable();

            $table->text('symptoms_list')->nullable();
            $table->text('symptoms_onset_list')->nullable();
            $table->double('fever_highest_temp')->nullable();
            $table->tinyInteger('rash_isMaculopapular')->nullable();
            $table->tinyInteger('rash_isPetechia')->nullable();
            $table->tinyInteger('rash_isPurpura')->nullable();

            $table->tinyInteger('fever')->nullable();
            $table->date('fever_onset')->nullable();
            $table->text('fever_remarks')->nullable();
            $table->tinyInteger('rash')->nullable();
            $table->tinyInteger('bloody_stool')->nullable();
            $table->text('other_symptoms_onset_remarks')->nullable();

            $table->date('rash_onset')->nullable();
            $table->text('rash_remarks')->nullable();
            $table->tinyInteger('cough')->nullable();
            $table->date('cough_onset')->nullable();
            $table->text('cough_remarks')->nullable();
            $table->tinyInteger('colds')->nullable();
            $table->date('colds_onset')->nullable();
            $table->text('colds_remarks')->nullable();
            $table->tinyInteger('conjunctivitis')->nullable();
            $table->date('conjunctivitis_onset')->nullable();
            $table->text('conjunctivitis_remarks')->nullable();
            $table->tinyInteger('mouthsore')->nullable();
            $table->date('mouthsore_onset')->nullable();
            $table->text('mouthsore_remarks')->nullable();
            $table->tinyInteger('sorethroat')->nullable();
            $table->date('sorethroat_onset')->nullable();
            $table->text('sorethroat_remarks')->nullable();
            $table->tinyInteger('lossoftaste')->nullable();
            $table->date('lossoftaste_onset')->nullable();
            $table->text('lossoftaste_remarks')->nullable();
            $table->tinyInteger('lossofsmell')->nullable();
            $table->date('lossofsmell_onset')->nullable();
            $table->text('lossofsmell_remarks')->nullable();
            $table->tinyInteger('headache')->nullable();
            $table->date('headache_onset')->nullable();
            $table->text('headache_remarks')->nullable();
            $table->tinyInteger('jointpain')->nullable();
            $table->date('jointpain_onset')->nullable();
            $table->text('jointpain_remarks')->nullable();
            $table->tinyInteger('musclepain')->nullable();
            $table->date('musclepain_onset')->nullable();
            $table->text('musclepain_remarks')->nullable();
            $table->tinyInteger('diarrhea')->nullable();
            $table->date('diarrhea_onset')->nullable();
            $table->text('diarrhea_remarks')->nullable();
            $table->tinyInteger('abdominalpain')->nullable();
            $table->date('abdominalpain_onset')->nullable();
            $table->text('abdominalpain_remarks')->nullable();
            $table->tinyInteger('vomiting')->nullable();
            $table->date('vomiting_onset')->nullable();
            $table->text('vomiting_remarks')->nullable();
            $table->tinyInteger('weaknessofextremities')->nullable();
            $table->date('weaknessofextremities_onset')->nullable();
            $table->text('weaknessofextremities_remarks')->nullable();
            $table->tinyInteger('paralysis')->nullable();
            $table->date('paralysis_onset')->nullable();
            $table->text('paralysis_remarks')->nullable();
            $table->tinyInteger('alteredmentalstatus')->nullable();
            $table->date('alteredmentalstatus_onset')->nullable();
            $table->text('alteredmentalstatus_remarks')->nullable();
            $table->tinyInteger('animalbite')->nullable();
            $table->date('animalbite_onset')->nullable();
            $table->text('animalbite_remarks')->nullable();
            $table->tinyInteger('anorexia')->nullable();
            $table->date('anorexia_onset')->nullable();
            $table->text('anorexia_remarks')->nullable();
            $table->tinyInteger('jaundice')->nullable();
            $table->date('jaundice_onset')->nullable();
            $table->text('jaundice_remarks')->nullable();
            $table->tinyInteger('nausea')->nullable();
            $table->date('nausea_onset')->nullable();
            $table->text('nausea_remarks')->nullable();
            $table->tinyInteger('fatigue')->nullable();
            $table->date('fatigue_onset')->nullable();
            $table->text('fatigue_remarks')->nullable();
            $table->tinyInteger('dyspnea')->nullable();
            $table->date('dyspnea_onset')->nullable();
            $table->text('dyspnea_remarks')->nullable();
            $table->tinyInteger('other_symptoms')->nullable();
            $table->date('other_symptoms_onset')->nullable();

            $table->tinyInteger('is_hospitalized')->nullable();
            $table->string('hospital_name')->nullable();
            $table->date('date_admitted')->nullable();
            $table->date('date_released')->nullable();

            $table->text('dcnote')->nullable();
            $table->text('dcnote_assessment')->nullable();
            $table->text('diagnosis_type')->nullable();
            $table->text('dcnote_plan')->nullable();
            $table->text('main_diagnosis')->nullable(); //icd10 code main diagnosis
            $table->text('other_diagnosis')->nullable();
            $table->text('dcnote_diagprocedure')->nullable(); //icd10 other diagnosis
            $table->text('rx')->nullable();
            $table->text('remarks')->nullable();

            $table->string('prescribe_option', 1)->nullable();
            $table->text('prescription_list')->nullable();

            $table->text('laboratory_request_list')->nullable();
            $table->text('imaging_request_list')->nullable();
            $table->text('alert_list')->nullable();
            $table->text('alert_ifdisability_list')->nullable();
            $table->text('alert_description')->nullable();

            $table->text('comorbid_list')->nullable();
            $table->text('firstdegree_comorbid_list')->nullable();

            $table->text('generated_susdiseaselist')->nullable();

            $table->text('name_of_interviewer')->nullable();
            $table->text('name_of_physician')->nullable();
            $table->text('other_doctor')->nullable();
            $table->text('dru_name')->nullable();
            
            $table->tinyInteger('brgy_verified')->default(0);
            $table->dateTime('brgy_verified_date')->nullable();
            $table->foreignId('brgy_verified_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->tinyInteger('cesu_verified')->default(0);
            $table->dateTime('cesu_verified_date')->nullable();
            $table->foreignId('cesu_verified_by')->nullable()->constrained('users')->onDelete('cascade');

            $table->integer('age_years')->nullable();
            $table->integer('age_months')->nullable();
            $table->integer('age_days')->nullable();

            $table->string('outcome')->nullable();
            $table->date('outcome_recovered_date')->nullable();
            $table->date('outcome_died_date')->nullable();

            $table->string('procedure_done')->nullable();
            $table->string('disposition')->nullable();
            $table->string('is_discharged', 1)->default('N');
            $table->date('date_discharged')->nullable();
            $table->text('tags')->nullable();

            $table->tinyInteger('medcert_enabled')->default(0);
            $table->date('medcert_generated_date')->nullable();
            $table->date('medcert_validity_date')->nullable();
            $table->date('medcert_start_date')->nullable();
            $table->date('medcert_end_date')->nullable();
            $table->string('medcert_purpose')->nullable();

            $table->text('document_file')->nullable();
            $table->text('qr');

            $table->tinyInteger('is_listed_notifiable')->default(0); //FOR DAILY EMAIL CHECK
            $table->tinyInteger('email_notified')->default(0); //ALSO FOR DAILY EMAIL CHECK
            $table->tinyInteger('view_notified')->default(0);

            $table->tinyInteger('sent_pidsr')->default(0); //SENT/IMPORTED TO PIDSR SYSTEM
            
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('facility_id')->nullable()->constrained('doh_facilities')->onDelete('cascade');
            $table->foreignId('medical_event_id')->nullable()->constrained('medical_events')->onDelete('cascade');
            //$table->foreignId('created_on_branch')->nullable()->constrained('pharmacy_branches')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');

            $table->string('ics_ticketstatus')->default('OPEN');
            $table->foreignId('ics_grabbedby')->nullable()->constrained('users')->onDelete('cascade');
            $table->dateTime('ics_grabbed_date')->nullable();
            $table->foreignId('ics_finishedby')->nullable()->constrained('users')->onDelete('cascade');
            $table->dateTime('ics_finished_date')->nullable();

            $table->tinyInteger('encodedfrom_tbdots')->default(0);
            $table->text('disease_tag')->nullable();
            //$table->text('auto_disease_tag')->nullable();
            $table->text('alreadyimported_disease_tag')->nullable();
            $table->text('received_edcs_tag')->nullable();
            $table->tinyInteger('from_evacuationcenter')->default(0);
            $table->bigInteger('ec_familyhead_id')->nullable();
            $table->bigInteger('ec_familymember_id')->nullable();
            //$table->tinyInteger('transferredto_edcs')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('syndromic_records');
    }
}
