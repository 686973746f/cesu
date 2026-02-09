<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInhouseFamilyPlanningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inhouse_family_plannings', function (Blueprint $table) {
            $table->id();
            $table->char('enabled', 1)->default('Y');
            $table->foreignId('patient_id')->constrained('syndromic_patients')->onDelete('cascade');
            $table->foreignId('facility_id')->constrained('doh_facilities')->onDelete('cascade');
            $table->date('registration_date');
            $table->string('age_group', 20);
            
            $table->string('client_type');
            $table->string('source');
            $table->string('previous_method')->nullable();
            $table->string('current_method')->nullable();
            $table->char('is_permanent', 1)->default('N');

            $table->char('is_dropout', 1)->default('N');
            $table->date('dropout_date')->nullable();
            $table->string('dropout_reason')->nullable();

            $table->string('remarks')->nullable();
            $table->string('system_remarks')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('request_uuid')->unique();

            $table->date('bdate_fixed')->nullable();
            $table->integer('age_years')->nullable();
            $table->integer('age_months')->nullable();
            $table->integer('age_days')->nullable();

            $table->char('is_locked')->default('N');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inhouse_family_plannings');
    }
}
