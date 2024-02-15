<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyndromicDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syndromic_doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->nullable()->constrained('doh_facilities')->onDelete('cascade');
            $table->string('doctor_name');
            $table->string('dru_name');
            $table->string('gender', 1)->nullable();
            $table->date('bdate')->nullable();
            $table->string('position_ref')->nullable();
            $table->string('position')->nullable();

            $table->string('hired_by')->nullable();
            $table->string('employment_status')->nullable();
            $table->string('active_in_service', 1)->default('Y');
            $table->string('current_user', 1)->nullable();

            $table->string('reg_no')->nullable(); //PRC LICENSE NUMBER
            $table->string('ptr_no')->nullable();
            $table->string('phic_no')->nullable();
            $table->string('phic_accre_code')->nullable();
            $table->string('s2_license')->nullable();
            $table->string('tin_no')->nullable();

            $table->text('catchment_brgy_list')->nullable(); //IF MIDWIFE
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
        Schema::dropIfExists('syndromic_doctors');
    }
}
