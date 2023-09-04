<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmacyPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacy_patients', function (Blueprint $table) {
            $table->id();
            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->date('bdate');
            $table->string('gender');
            $table->string('contact_number')->nullable();
            $table->string('contact_number2')->nullable();
            $table->string('email')->nullable();
            $table->string('philhealth')->nullable();

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

            $table->text('qr');
            $table->text('id_file')->nullable();
            $table->text('selfie_file')->nullable();

            $table->string('status')->default('ENABLED');
            $table->text('status_remarks')->nullable();
            $table->foreignId('pharmacy_branch_id')->constrained('pharmacy_branches')->onDelete('cascade');

            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('pharmacy_patients');
    }
}
