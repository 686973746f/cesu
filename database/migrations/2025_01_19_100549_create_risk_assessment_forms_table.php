<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiskAssessmentFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('risk_assessment_forms', function (Blueprint $table) {
            $table->id();
            
            $table->integer('year');
            $table->integer('month');
            $table->string('from_online', 1)->default('N');
            $table->foreignId('link_opdpatient_id')->nullable()->constrained('syndromic_patients')->onDelete('cascade');
            $table->date('assessment_date');
            $table->string('is_followup', 1)->default('N');

            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->string('sex', 1);
            $table->date('bdate');
            $table->integer('age_years');
            $table->integer('age_months');
            $table->integer('age_days');
            $table->string('street_purok')->nullable();
            $table->foreignId('address_brgy_code')->constrained('edcs_brgies')->onDelete('cascade');

            $table->string('occupation')->nullable();
            $table->string('educational_attainment')->nullable();

            $table->string('fh_hypertension', 1)->default('N');
            $table->string('fh_stroke', 1)->default('N');
            $table->string('fh_heartattack', 1)->default('N');
            $table->string('fh_diabetes', 1)->default('N');
            $table->string('fh_asthma', 1)->default('N');
            $table->string('fh_cancer', 1)->default('N');
            $table->string('fh_kidneydisease', 1)->default('N');

            $table->string('smoking');
            $table->string('alcohol_intake', 1)->default('N');
            $table->string('excessive_alcohol_intake', 1)->default('N');
            $table->string('weight_classification');
            //$table->string('obese', 1)->default('N');
            //$table->string('overweight', 1)->default('N');
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('bmi')->nullable();
            
            $table->string('central_adiposity', 1)->default('N');
            $table->double('waist_cm')->nullable();
            $table->string('raised_bp', 1)->default('N');
            $table->double('systolic')->nullable();
            $table->double('diastolic')->nullable();
            $table->double('systolic2')->nullable();
            $table->double('diastolic2')->nullable();

            $table->string('high_fatsalt_intake', 1)->default('N');
            $table->string('vegetable_serving', 1)->default('N');
            $table->string('fruits_serving', 1)->default('N');
            $table->string('physical_activity', 1)->default('N');

            $table->string('heart_attack', 1)->default('N');
            $table->string('question1', 1)->default('N');
            $table->string('question2', 1)->default('N');
            $table->string('question3', 1)->default('N');
            $table->string('question4', 1)->default('N');
            $table->string('question5', 1)->default('N');
            $table->string('question6', 1)->default('N');
            $table->string('question7', 1)->default('N');

            $table->string('stroke_ortia', 1)->default('N');
            $table->string('question8', 1)->default('N');
            $table->string('diabetes', 1)->default('N');
            $table->string('diabetes_medication')->nullable();

            $table->string('polyphagia', 1)->default('N');
            $table->string('polydipsia', 1)->default('N');
            $table->string('polyuria', 1)->default('N');

            $table->string('raised_bloodglucose', 1)->default('N');
            $table->string('fbs_rbs')->nullable();
            $table->date('fbs_rbs_date')->nullable();

            $table->string('raised_bloodlipids', 1)->default('N');
            $table->string('cholesterol')->nullable();
            $table->date('cholesterol_date')->nullable();

            $table->string('urine_protein', 1)->default('N');
            $table->string('protein')->nullable();
            $table->date('protein_date')->nullable();

            $table->string('urine_ketones', 1)->default('N');
            $table->string('ketones')->nullable();
            $table->date('ketones_date')->nullable();

            $table->string('management')->nullable();
            $table->text('meds')->nullable();

            $table->string('sleep_greaterthan6', 1)->default('N');

            $table->string('date_followup')->nullable();
            $table->string('risk_level')->nullable();
            $table->text('finding')->nullable();
            $table->text('assessed_by')->nullable();
            $table->text('remarks')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('facility_id')->constrained('doh_facilities')->onDelete('cascade');
            $table->string('qr');
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
        Schema::dropIfExists('risk_assessment_forms');
    }
}
