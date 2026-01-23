<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInhouseMaternalCaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inhouse_maternal_cares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('syndromic_patients')->onDelete('cascade');
            $table->foreignId('facility_id')->constrained('doh_facilities')->onDelete('cascade');
            $table->date('registration_date');
            $table->string('highrisk', 1);

            $table->date('lmp')->nullable();
            $table->integer('gravida')->nullable();
            $table->integer('parity')->nullable();
            $table->date('edc')->nullable();

            $table->date('visit1_est')->nullable();
            $table->date('visit1')->nullable();
            $table->string('visit1_type')->nullable();
            $table->date('visit2_est')->nullable();
            $table->date('visit2')->nullable();
            $table->string('visit2_type')->nullable();
            $table->date('visit3_est')->nullable();
            $table->date('visit3')->nullable();
            $table->string('visit3_type')->nullable();
            $table->date('visit4_est')->nullable();
            $table->date('visit4')->nullable();
            $table->string('visit4_type')->nullable();
            $table->date('visit5_est')->nullable();
            $table->date('visit5')->nullable();
            $table->string('visit5_type')->nullable();
            $table->date('visit6_est')->nullable();
            $table->date('visit6')->nullable();
            $table->string('visit6_type')->nullable();
            $table->date('visit7_est')->nullable();
            $table->date('visit7')->nullable();
            $table->string('visit7_type')->nullable();
            $table->date('visit8_est')->nullable();
            $table->date('visit8')->nullable();
            $table->string('visit8_type')->nullable();

            $table->double('height')->nullable();
            $table->double('weight')->nullable();

            $table->string('trans_remarks')->nullable();

            $table->date('td1')->nullable();
            $table->string('td1_type')->nullable();
            $table->date('td2')->nullable();
            $table->string('td2_type')->nullable();
            $table->date('td3')->nullable();
            $table->string('td3_type')->nullable();
            $table->date('td4')->nullable();
            $table->string('td4_type')->nullable();
            $table->date('td5')->nullable();
            $table->string('td5_type')->nullable();

            $table->date('deworming_date')->nullable();

            $table->date('ifa1_date')->nullable();
            $table->integer('ifa1_dosage')->nullable();
            $table->string('ifa1_type')->nullable();
            $table->date('ifa2_date')->nullable();
            $table->integer('ifa2_dosage')->nullable();
            $table->string('ifa2_type')->nullable();
            $table->date('ifa3_date')->nullable();
            $table->integer('ifa3_dosage')->nullable();
            $table->string('ifa3_type')->nullable();
            $table->date('ifa4_date')->nullable();
            $table->integer('ifa4_dosage')->nullable();
            $table->string('ifa4_type')->nullable();
            $table->date('ifa5_date')->nullable();
            $table->integer('ifa5_dosage')->nullable();
            $table->string('ifa5_type')->nullable();
            $table->date('ifa6_date')->nullable();
            $table->integer('ifa6_dosage')->nullable();
            $table->string('ifa6_type')->nullable();

            $table->date('mms1_date')->nullable();
            $table->integer('mms1_dosage')->nullable();
            $table->string('mms1_type')->nullable();
            $table->date('mms2_date')->nullable();
            $table->integer('mms2_dosage')->nullable();
            $table->string('mms2_type')->nullable();
            $table->date('mms3_date')->nullable();
            $table->integer('mms3_dosage')->nullable();
            $table->string('mms3_type')->nullable();
            $table->date('mms4_date')->nullable();
            $table->integer('mms4_dosage')->nullable();
            $table->string('mms4_type')->nullable();
            $table->date('mms5_date')->nullable();
            $table->integer('mms5_dosage')->nullable();
            $table->string('mms5_type')->nullable();
            $table->date('mms6_date')->nullable();
            $table->integer('mms6_dosage')->nullable();
            $table->string('mms6_type')->nullable();

            $table->date('calcium1_date')->nullable();
            $table->integer('calcium1_dosage')->nullable();
            $table->string('calcium1_type')->nullable();
            $table->date('calcium2_date')->nullable();
            $table->integer('calcium2_dosage')->nullable();
            $table->string('calcium2_type')->nullable();
            $table->date('calcium3_date')->nullable();
            $table->integer('calcium3_dosage')->nullable();
            $table->string('calcium3_type')->nullable();

            $table->date('syphilis_date')->nullable();
            $table->char('syphilis_result', 1)->nullable();
            $table->date('hiv_date')->nullable();
            $table->char('hiv_result', 1)->nullable();
            $table->date('hb_date')->nullable();
            $table->char('hb_result', 1)->nullable();
            $table->date('cbc_date')->nullable();
            $table->char('cbc_result', 1)->nullable();
            $table->date('diabetes_date')->nullable();
            $table->char('diabetes_result', 1)->nullable();

            $table->date('pregnancy_terminated_date')->nullable();
            $table->char('outcome', 2)->nullable();
            $table->char('delivery_type', 2)->nullable();

            $table->double('birth_weight')->nullable();

            $table->string('place_of_delivery')->nullable();
            $table->string('facility_type')->nullable();
            $table->char('bcemoncapable', 1)->nullable();
            $table->string('attendant')->nullable();
            $table->dateTime('delivery_date')->nullable();

            $table->date('pnc1')->nullable();
            $table->date('pnc2')->nullable();
            $table->date('pnc3')->nullable();
            $table->date('pnc4')->nullable();

            $table->date('pp_td1')->nullable();
            $table->integer('pp_td1_dosage')->nullable();
            $table->date('pp_td2')->nullable();
            $table->integer('pp_td2_dosage')->nullable();
            $table->date('pp_td3')->nullable();
            $table->integer('pp_td3_dosage')->nullable();
            $table->date('pp_td4')->nullable();
            $table->integer('pp_td4_dosage')->nullable();

            $table->date('vita')->nullable();
            $table->string('pp_remarks')->nullable();

            $table->text('system_remarks')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('request_uuid')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inhouse_maternal_cares');
    }
}
