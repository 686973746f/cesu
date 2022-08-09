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
            
            $table->date('morbidity_month');
            $table->date('date_reported');
            $table->text('epid_number')->nullable();
            $table->date('date_investigation');
            $table->string('dru_name');
            $table->string('dru_region');
            $table->string('dru_province');
            $table->text('dru_address');

            $table->text('criteria');
            $table->text('source');
            $table->text('type');
            $table->text('laboratory_id')->nullable();

            $table->date('date_onset');
            $table->date('date_admitted');

            $table->string('admission_er');
            $table->date('admission_er_date')->nullable();
            $table->string('admission_ward');
            $table->date('admission_ward_date')->nullable();
            $table->string('admission_icu');
            $table->date('admission_icu_date')->nullable();
            $table->date('date_discharge')->nullable();

            $table->string('q1_yn');
            $table->string('q1_specify')->nullable();
            $table->date('q1_date_travel')->nullable();
            $table->string('q1_flightno')->nullable();
            $table->date('q1_date_arrival')->nullable();
            $table->string('q1_pointandexitentry')->nullable();

            $table->string('q2_yn');
            $table->string('q2_specify')->nullable();
            $table->date('q2_date_travel')->nullable();
            $table->string('q2_flightno')->nullable();
            $table->date('q2_date_arrival')->nullable();
            $table->string('q2_pointandexitentry')->nullable();

            $table->string('q3_yn');
            $table->date('q3_date_onset')->nullable();

            $table->string('q4_yn');
            $table->date('q4_date_onset')->nullable();
            $table->string('q4_days_duration')->nullable();

            $table->text('q5_list')->nullable();
            $table->string('q51_yn');
            $table->string('q52_yn');
            $table->string('q53_yn');
            $table->string('q54_yn');

            $table->text('q6_localisaiton');
            $table->text('q6_otherareas')->nullable();

            $table->text('symptoms_list')->nullable();

            $table->string('hexp_i1_yn');
            $table->string('hexp_i1_lname')->nullable();
            $table->string('hexp_i1_fname')->nullable();
            $table->string('hexp_i1_relationship')->nullable();

            $table->string('hexp_i2_yn');
            $table->string('hexx_i2_specify')->nullable();
            $table->date('hexp_i2_date')->nullable();
            $table->text('hexp_i2_type_of_contact')->nullable();
            $table->text('hexp_i2_type_of_contact_ifothers')->nullable();

            $table->string('remarks')->nullable();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('records_id')->constrained()->onDelete('cascade');

            $table->timestamps();
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
