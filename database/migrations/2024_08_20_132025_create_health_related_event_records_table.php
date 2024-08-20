<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthRelatedEventRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('health_related_event_records', function (Blueprint $table) {
            $table->id();

            $table->string('enabled', 1);
            $table->foreignId('patient_id')->constrained('health_related_event_patients')->onDelete('cascade');

            $table->date('date_onset')->nullable();
            $table->string('admitted', 1);
            $table->string('admittedfacility_name')->nullable();
            $table->date('date_admittedconsulted');

            $table->string('vog_dizziness', 1)->nullable();
            $table->string('vog_dob', 1)->nullable();
            $table->string('vog_cough', 1)->nullable();
            $table->string('vog_eyeirritation', 1)->nullable();
            $table->string('vog_throatirritation', 1)->nullable();
            $table->string('vog_others', 1)->nullable();
            $table->string('vog_others_specify')->nullable();

            $table->string('outcome', 1)->nullable();
            $table->text('remarks')->nullable();

            $table->integer('age_years')->nullable();
            $table->integer('age_months')->nullable();
            $table->integer('age_days')->nullable();

            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('health_related_event_records');
    }
}
