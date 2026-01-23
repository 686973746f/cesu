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
            $table->foreignId('patient_id')->constrained('syndromic_patients')->onDelete('cascade');
            $table->foreignId('facility_id')->constrained('doh_facilities')->onDelete('cascade');
            $table->date('registration_date');

            $table->foreignId('maternalcare_id')->nullable()->constrained('inhouse_maternal_cares')->onDelete('cascade');

            $table->string('cpab1', 1)->nullable();
            $table->string('cpab2', 1)->nullable();
            $table->date('bcg1')->nullable();
            $table->date('bcg2')->nullable();
            $table->date('hepab1')->nullable();
            $table->date('hepab2')->nullable();
            $table->date('dpt1_est')->nullable();
            $table->date('dpt1')->nullable();
            $table->date('dpt2_est')->nullable();
            $table->date('dpt2')->nullable();
            $table->date('dpt3_est')->nullable();
            $table->date('dpt3')->nullable();

            $table->date('opv1_est')->nullable();
            $table->date('opv1')->nullable();
            $table->date('opv2_est')->nullable();
            $table->date('opv2')->nullable();
            $table->date('opv3_est')->nullable();
            $table->date('opv3')->nullable();

            $table->date('ipv1_est')->nullable();
            $table->date('ipv1')->nullable();
            $table->date('ipv2_est')->nullable();
            $table->date('ipv2')->nullable();
            $table->date('ipv3_est')->nullable();
            $table->date('ipv3')->nullable();
            
            $table->date('pcv1_est')->nullable();
            $table->date('pcv1')->nullable();
            $table->date('pcv2_est')->nullable();
            $table->date('pcv2')->nullable();
            $table->date('pcv3_est')->nullable();
            $table->date('pcv3')->nullable();
            $table->date('mmr1_est')->nullable();
            $table->date('mmr1')->nullable();
            $table->date('mmr2_est')->nullable();
            $table->date('mmr2')->nullable();

            $table->text('remarks')->nullable();
            $table->text('system_remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('request_uuid')->unique();
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
