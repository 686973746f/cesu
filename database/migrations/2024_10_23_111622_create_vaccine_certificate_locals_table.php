<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaccineCertificateLocalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaccine_certificate_locals', function (Blueprint $table) {
            $table->id();
            $table->string('enabled', 1)->default('Y');
            $table->string('control_no');

            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable();
            $table->string('gender');

            $table->date('bdate');
            $table->string('contact_number');
            $table->string('category');
            $table->string('vaxcard_uniqueid')->nullable();

            $table->text('address_region_code');
            $table->text('address_region_text');
            $table->text('address_province_code');
            $table->text('address_province_text');
            $table->text('address_muncity_code');
            $table->text('address_muncity_text');
            $table->text('address_brgy_code');
            $table->text('address_brgy_text');

            $table->string('process_dose1', 1)->default('N');
            $table->string('dose1_city', 1)->default('N');
            $table->date('dose1_vaccination_date')->nullable();
            $table->string('dose1_vaccine_manufacturer_name')->nullable();
            $table->string('dose1_batch_number')->nullable();
            //$table->string('dose1_lotno')->nullable();
            //$table->string('dose1_inmainlgu_yn');
            //$table->string('dose1_bakuna_center_text')->nullable();
            //$table->string('dose1_bakuna_center_code')->nullable();
            $table->string('dose1_vaccinator_name')->nullable();
            $table->string('dose1_vaccinator_licenseno')->nullable();

            $table->string('process_dose2', 1)->default('N');
            $table->string('dose2_city', 1)->default('N');
            $table->date('dose2_vaccination_date')->nullable();
            $table->string('dose2_vaccine_manufacturer_name')->nullable();
            $table->string('dose2_batch_number')->nullable();
            //$table->string('dose2_lotno')->nullable();
            //$table->string('dose2_inmainlgu_yn')->nullable();
            //$table->string('dose2_bakuna_center_text')->nullable();
            //$table->string('dose2_bakuna_center_code')->nullable();
            $table->string('dose2_vaccinator_name')->nullable();
            $table->string('dose2_vaccinator_licenseno')->nullable();

            $table->string('process_dose3', 1)->default('N');
            $table->string('dose3_city', 1)->default('N');
            $table->date('dose3_vaccination_date')->nullable();
            $table->string('dose3_vaccine_manufacturer_name')->nullable();
            $table->string('dose3_batch_number')->nullable();
            //$table->string('dose3_lotno')->nullable();
            //$table->string('dose3_inmainlgu_yn')->nullable();
            //$table->string('dose3_bakuna_center_text')->nullable();
            //$table->string('dose3_bakuna_center_code')->nullable();
            $table->string('dose3_vaccinator_name')->nullable();
            $table->string('dose3_vaccinator_licenseno')->nullable();

            $table->string('process_dose4', 1)->default('N');
            $table->string('dose4_city', 1)->default('N');
            $table->date('dose4_vaccination_date')->nullable();
            $table->string('dose4_vaccine_manufacturer_name')->nullable();
            $table->string('dose4_batch_number')->nullable();
            //$table->string('dose4_lotno')->nullable();
            //$table->string('dose4_inmainlgu_yn')->nullable();
            //$table->string('dose4_bakuna_center_text')->nullable();
            //$table->string('dose4_bakuna_center_code')->nullable();
            $table->string('dose4_vaccinator_name')->nullable();
            $table->string('dose4_vaccinator_licenseno')->nullable();

            $table->string('process_dose5', 1)->default('N');
            $table->string('dose5_city', 1)->default('N');
            $table->date('dose5_vaccination_date')->nullable();
            $table->string('dose5_vaccine_manufacturer_name')->nullable();
            $table->string('dose5_batch_number')->nullable();
            //$table->string('dose5_lotno')->nullable();
            //$table->string('dose5_inmainlgu_yn')->nullable();
            //$table->string('dose5_bakuna_center_text')->nullable();
            //$table->string('dose5_bakuna_center_code')->nullable();
            $table->string('dose5_vaccinator_name')->nullable();
            $table->string('dose5_vaccinator_licenseno')->nullable();

            $table->text('remarks')->nullable();

            $table->string('hash');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('facility_id')->nullable()->constrained('doh_facilities')->onDelete('cascade');
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
        Schema::dropIfExists('vaccine_certificate_locals');
    }
}
