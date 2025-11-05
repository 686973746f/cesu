<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSbsPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbs_patients', function (Blueprint $table) {
            $table->id();
            $table->string('enabled', 1)->default('Y');
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');

            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->string('sex', 1);
            $table->date('bdate');

            $table->integer('age_years');
            $table->integer('age_months');
            $table->integer('age_days');

            $table->string('patient_type');
            $table->string('staff_designation')->nullable();
            $table->foreignId('section_id')->nullable()->constrained('school_sections')->onDelete('cascade');

            $table->string('street_purok')->nullable();
            $table->foreignId('address_brgy_code')->constrained('edcs_brgies')->onDelete('cascade');

            $table->string('contact_no')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_contactno')->nullable();
            $table->string('is_pwd', 1)->default('N');
            $table->text('pwd_condition')->nullable();

            $table->text('remarks')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('qr');
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
        Schema::dropIfExists('sbs_patients');
    }
}
