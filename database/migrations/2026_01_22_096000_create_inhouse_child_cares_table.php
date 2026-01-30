<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInhouseChildCaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inhouse_child_cares', function (Blueprint $table) {
            $table->id();
            $table->char('enabled', 1)->default('Y');
            $table->foreignId('patient_id')->constrained('syndromic_patients')->onDelete('cascade');
            $table->string('mother_type', 1)->nullable();
            $table->foreignId('maternalcare_id')->nullable()->constrained('inhouse_maternal_cares')->onDelete('cascade');
            $table->string('mother_name')->nullable();
            
            $table->foreignId('facility_id')->constrained('doh_facilities')->onDelete('cascade');
            $table->date('registration_date');

            $table->string('cpab', 1)->nullable();
            $table->string('cpab_type')->nullable(); //open text

            $table->date('bcg1')->nullable();
            $table->string('bcg1_type')->nullable();
            $table->date('bcg2')->nullable();
            $table->string('bcg2_type')->nullable();
            $table->date('hepab1')->nullable();
            $table->string('hepab1_type')->nullable();
            $table->date('hepab2')->nullable();
            $table->string('hepab2_type')->nullable();

            $table->date('dpt1')->nullable();
            $table->string('dpt1_type')->nullable();
            $table->integer('dpt1_months')->nullable();
            $table->date('dpt2')->nullable();
            $table->string('dpt2_type')->nullable();
            $table->integer('dpt2_months')->nullable();
            $table->date('dpt3')->nullable();
            $table->string('dpt3_type')->nullable();
            $table->integer('dpt3_months')->nullable();

            $table->date('opv1')->nullable();
            $table->string('opv1_type')->nullable();
            $table->integer('opv1_months')->nullable();
            $table->date('opv2')->nullable();
            $table->string('opv2_type')->nullable();
            $table->integer('opv2_months')->nullable();
            $table->date('opv3')->nullable();
            $table->string('opv3_type')->nullable();
            $table->integer('opv3_months')->nullable();

            $table->date('ipv1')->nullable();
            $table->string('ipv1_type')->nullable();
            $table->integer('ipv1_months')->nullable();
            $table->date('ipv2')->nullable();
            $table->string('ipv2_type')->nullable();
            $table->integer('ipv2_months')->nullable();
            $table->date('ipv3')->nullable();
            $table->string('ipv3_type')->nullable();
            $table->integer('ipv3_months')->nullable();
            
            $table->date('pcv1')->nullable();
            $table->string('pcv1_type')->nullable();
            $table->integer('pcv1_months')->nullable();
            $table->date('pcv2')->nullable();
            $table->string('pcv2_type')->nullable();
            $table->integer('pcv2_months')->nullable();
            $table->date('pcv3')->nullable();
            $table->string('pcv3_type')->nullable();
            $table->integer('pcv3_months')->nullable();
            $table->date('mmr1')->nullable();
            $table->string('mmr1_type')->nullable();
            $table->integer('mmr1_months')->nullable();
            $table->date('mmr2')->nullable();
            $table->string('mmr2_type')->nullable();
            $table->integer('mmr2_months')->nullable();

            $table->date('fic')->nullable();
            $table->date('cic')->nullable();

            $table->text('remarks')->nullable();
            $table->text('system_remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('request_uuid')->unique();

            $table->integer('age_years')->nullable();
            $table->integer('age_months')->nullable();
            $table->integer('age_days')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inhouse_child_cares');
    }
}
