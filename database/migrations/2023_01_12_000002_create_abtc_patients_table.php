<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbtcPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abtc_patients', function (Blueprint $table) {
            $table->id();
            $table->string('register_status')->default('VERIFIED');
            $table->string('referred_from')->nullable();
            $table->date('referred_date')->nullable();
            $table->tinyInteger('enabled')->default(1);
            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->date('bdate')->nullable();
            $table->integer('age')->nullable();
            $table->string('gender');
            $table->string('is_pregnant', 1)->default('N');
            $table->string('contact_number')->nullable();
            $table->string('philhealth')->nullable();
            $table->string('philhealth_statustype')->nullable();

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

            $table->string('is_indg', 1)->default('N');
            $table->string('is_4ps', 1)->default('N');
            $table->string('is_nhts', 1)->default('N');
            $table->string('is_seniorcitizen', 1)->default('N');
            $table->string('is_pwd', 1)->default('N');
            $table->string('is_singleparent', 1)->default('N');
            $table->string('is_others', 1)->default('N');
            $table->string('is_others_specify')->nullable();

            $table->string('linkphilhealth_lname')->nullable();
            $table->string('linkphilhealth_fname')->nullable();
            $table->string('linkphilhealth_mname')->nullable();
            $table->string('linkphilhealth_suffix')->nullable();
            $table->string('linkphilhealth_sex', 1)->nullable();
            $table->date('linkphilhealth_bdate')->nullable();
            $table->string('linkphilhealth_phnumber')->nullable();
            $table->string('linkphilhealth_relationship')->nullable();

            $table->string('linkphilhealth_hasjob', 1)->default('N');
            $table->string('linkphilhealth_businessname')->nullable();
            $table->string('linkphilhealth_pen')->nullable();
            $table->string('linkphilhealth_contactno')->nullable();
            $table->string('linkphilhealth_employer_name')->nullable();
            $table->string('linkphilhealth_employer_position')->nullable();
            
            $table->text('remarks')->nullable();
            $table->text('qr');
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->ipAddress('ip');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abtc_patients');
    }
}