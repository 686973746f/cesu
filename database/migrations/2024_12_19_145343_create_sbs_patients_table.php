<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSbsPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbs_patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->date('date_reported');

            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->string('sex', 1);
            $table->date('bdate');
            $table->integer('age_years');
            $table->integer('age_months');
            $table->integer('age_days');

            $table->string('patient_type');
            $table->string('staff_designation')->nullable();
            $table->string('grade_level')->nullable();
            $table->string('section')->nullable();

            $table->string('street_purok')->nullable();
            $table->foreignId('address_brgy_code')->constrained('edcs_brgies')->onDelete('cascade');

            $table->string('contact_no')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_contactno')->nullable();
            $table->string('is_pwd', 1)->default('N');
            $table->text('pwd_condition')->nullable();

            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('bp_systolic')->nullable();
            $table->string('bp_diastolic')->nullable();
            $table->string('had_dinner_yesterday', 1)->nullable();
            $table->string('had_breakfast_today', 1)->nullable();
            $table->string('had_lunch_today', 1)->nullable();
            $table->date('onset_illness_date');
            $table->text('signs_and_symptoms')->nullable();
            $table->string('fever_temperature')->nullable();
            $table->text('signs_and_symptoms_others')->nullable();
            $table->text('remarks')->nullable();
            $table->text('cesu_remarks')->nullable();

            $table->string('outcome')->nullable(); //SENT HOME, RECOVERED
            $table->date('date_senthome')->nullable();
            $table->date('date_recovered')->nullable();

            $table->string('reported_by');
            $table->string('reported_by_position');
            $table->string('reported_by_contactno');

            $table->string('enabled', 1)->default('Y');
            $table->string('is_verified', 1)->default('N');
            $table->string('is_sent', 1)->default('N');

            $table->text('suspected_disease_tag')->nullable();
            $table->integer('report_year');
            $table->integer('report_month');
            $table->integer('report_week');

            $table->string('admitted', 1)->default('N');
            $table->date('date_admitted')->nullable();
            $table->string('admitted_facility')->nullable();

            $table->string('had_checkuponfacilityafter', 1)->default('N');
            $table->string('name_facility')->nullable();
            $table->string('qr');

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('sbs_patients');
    }
}
