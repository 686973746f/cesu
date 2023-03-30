<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaxcertConcernsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaxcert_concerns', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('PENDING');
            $table->string('vaxcert_refno')->nullable();
            $table->string('category');

            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable();
            $table->string('gender');

            $table->date('bdate');
            $table->string('contact_number');
            $table->string('email')->nullable();

            $table->string('comorbidity')->nullable();
            $table->string('pwd_yn')->default('N');

            $table->string('guardian_name')->nullable();

            $table->text('address_region_code');
            $table->text('address_region_text');
            $table->text('address_province_code');
            $table->text('address_province_text');
            $table->text('address_muncity_code');
            $table->text('address_muncity_text');
            $table->text('address_brgy_code');
            $table->text('address_brgy_text');

            $table->date('dose1_date');
            $table->string('dose1_manufacturer');
            $table->string('dose1_batchno')->nullable();
            $table->string('dose1_lotno')->nullable();
            $table->string('dose1_inmainlgu_yn');
            $table->string('dose1_bakuna_center_text')->nullable();
            $table->string('dose1_bakuna_center_code')->nullable();
            $table->string('dose1_vaccinator_name')->nullable();

            $table->date('dose2_date')->nullable();
            $table->string('dose2_manufacturer')->nullable();
            $table->string('dose2_batchno')->nullable();
            $table->string('dose2_lotno')->nullable();
            $table->string('dose2_inmainlgu_yn')->nullable();
            $table->string('dose2_bakuna_center_text')->nullable();
            $table->string('dose2_bakuna_center_code')->nullable();
            $table->string('dose2_vaccinator_name')->nullable();

            $table->date('dose3_date')->nullable();
            $table->string('dose3_manufacturer')->nullable();
            $table->string('dose3_batchno')->nullable();
            $table->string('dose3_lotno')->nullable();
            $table->string('dose3_inmainlgu_yn')->nullable();
            $table->string('dose3_bakuna_center_text')->nullable();
            $table->string('dose3_bakuna_center_code')->nullable();
            $table->string('dose3_vaccinator_name')->nullable();

            $table->date('dose4_date')->nullable();
            $table->string('dose4_manufacturer')->nullable();
            $table->string('dose4_batchno')->nullable();
            $table->string('dose4_lotno')->nullable();
            $table->string('dose4_inmainlgu_yn')->nullable();
            $table->string('dose4_bakuna_center_text')->nullable();
            $table->string('dose4_bakuna_center_code')->nullable();
            $table->string('dose4_vaccinator_name')->nullable();

            $table->string('concern_type');
            $table->text('concern_msg');

            $table->text('id_file');
            $table->text('vaxcard_file');
            $table->string('vaxcard_uniqueid')->nullable();

            $table->string('sys_code'); //QR

            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('vaxcert_concerns');
    }
}
