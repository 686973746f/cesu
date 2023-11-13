<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDohFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doh_facilities', function (Blueprint $table) {
            $table->id();
            $table->string('healthfacility_code')->unique();
            $table->string('healthfacility_code_short')->unique();
            $table->text('facility_name');
            $table->text('facility_name_old1')->nullable();
            $table->text('facility_name_old2')->nullable();
            $table->text('facility_name_old3')->nullable();
            $table->string('major_type');
            $table->string('facility_type');
            $table->string('ownership_type');
            $table->string('subclassification_government')->nullable();
            $table->string('subclassification_private')->nullable();
            $table->text('address_street')->nullable();
            $table->text('address_building')->nullable();
            $table->text('address_region')->nullable();
            $table->text('address_region_psgc')->nullable();
            $table->text('address_province')->nullable();
            $table->text('address_province_psgc')->nullable();
            $table->text('address_muncity')->nullable();
            $table->text('address_muncity_psgc')->nullable();
            $table->text('address_barangay')->nullable();
            $table->text('address_barangay_psgc')->nullable();
            $table->text('zip_code')->nullable();
            $table->string('landline')->nullable();
            $table->string('landline2')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('email2')->nullable();
            $table->text('website')->nullable();
            $table->text('service_capability')->nullable();
            $table->text('bed_capacity')->nullable();
            $table->text('licensing_status')->nullable();
            $table->text('validity_date')->nullable();
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
        Schema::dropIfExists('doh_facilities');
    }
}