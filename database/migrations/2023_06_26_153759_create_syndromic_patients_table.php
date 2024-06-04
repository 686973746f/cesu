<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyndromicPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syndromic_patients', function (Blueprint $table) {
            $table->id();
            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->date('bdate');
            $table->string('gender');
            $table->string('cs');
            $table->string('contact_number')->nullable();
            $table->string('contact_number2')->nullable();
            $table->string('email')->nullable();

            $table->tinyInteger('isph_member')->nullable();
            $table->string('philhealth')->nullable();
            $table->string('philhealth_statustype')->nullable();

            $table->string('occupation')->nullable();
            $table->string('occupation_place')->nullable();

            $table->string('spouse_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('father_name')->nullable();

            $table->string('family_member')->nullable();
            $table->string('is_indg', 1)->default('N');
            $table->string('blood_type')->nullable();

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

            $table->string('ifminor_resperson')->nullable();
            $table->string('ifminor_resrelation')->nullable();

            $table->tinyInteger('is_lgustaff')->default(0);
            $table->text('lgu_office_name')->nullable();

            $table->string('qr');
            $table->string('unique_opdnumber')->nullable();
            $table->string('id_presented')->nullable();
            $table->string('id_file')->nullable();
            $table->string('selfie_file')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('facility_id')->nullable()->constrained('doh_facilities')->onDelete('cascade');
            //$table->foreignId('created_on_branch')->nullable()->constrained('pharmacy_branches')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->text('shared_access_list')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('syndromic_patients');
    }
}
