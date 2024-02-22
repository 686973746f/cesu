<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQesSubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qes_subs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qes_main_id')->constrained('qes_mains')->onDelete('cascade');

            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->integer('age');
            $table->string('sex', 1);
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

            $table->string('occupation')->nullable();
            $table->string('placeof_work_school')->nullable();

            $table->string('has_symptoms', 1)->default('N');
            $table->datetime('onset_datetime')->nullable();
            $table->integer('illness_duration')->nullable();
            $table->date('diagnosis_date')->nullable();
            $table->string('hospitalized', 1)->default('N');
            $table->date('admission_date')->nullable();
            $table->date('discharge_date')->nullable();
            $table->string('hospital_name')->nullable();
            $table->string('outcome')->nullable();

            $table->string('lbm_3xday', 1)->default('N');
            $table->string('fever', 1)->default('N');
            $table->string('nausea', 1)->default('N');
            $table->string('vomiting', 1)->default('N');
            $table->string('bodyweakness', 1)->default('N');
            $table->string('abdominalcramps', 1)->default('N');
            $table->string('rectalpain', 1)->default('N');
            $table->string('tenesmus', 1)->default('N');
            $table->string('bloodystool', 1)->default('N');
            $table->string('brownish', 1)->default('N');
            $table->string('yellowish', 1)->default('N');
            $table->string('greenish', 1)->default('N');
            $table->string('others', 1)->default('N');
            $table->text('others_specify')->nullable();

            $table->string('volumeofstool')->nullable();
            $table->string('quantify')->nullable();

            $table->text('other_affected_names')->nullable();
            $table->text('other_affected_ages')->nullable();
            $table->text('other_affected_sex')->nullable();
            $table->text('other_affected_donset')->nullable();

            $table->string('question1', 1)->default('N');
            $table->string('question2', 1)->default('N');
            $table->string('question3', 1)->default('N');
            $table->string('question4', 1)->default('N');
            $table->string('question5', 1)->default('N');
            $table->string('question5_souce')->nullable();
            $table->text('question5_others')->nullable();
            $table->string('question6', 1)->default('N');
            $table->string('question6_where')->nullable();
            $table->string('question6_source')->nullable();
            $table->string('question7')->nullable();
            $table->string('question7_others')->nullable();
            $table->string('question8')->nullable();
            $table->string('question9', 1)->default('N');
            $table->string('question10')->nullable();
            $table->string('question11', 1)->default('N');
            $table->string('question12', 1)->default('N');
            
            $table->text('am_snacks_names')->nullable();
            $table->text('am_snacks_datetime')->nullable();

            $table->text('lunch_names')->nullable();
            $table->text('lunch_datetime')->nullable();
            
            $table->text('pm_snacks_names')->nullable();
            $table->text('pm_snacks_datetime')->nullable();

            $table->text('dinner_names')->nullable();
            $table->text('dinner_datetime')->nullable();

            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('qes_subs');
    }
}
