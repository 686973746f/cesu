<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyndromicLabResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syndromic_lab_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('syndromic_record_id')->constrained('syndromic_records')->onDelete('cascade');
            $table->string('case_code');
            $table->string('test_type');
            $table->string('test_type_others')->nullable();
            $table->string('manufacturer_name')->nullable();
            $table->date('date_collected');
            $table->date('date_transferred')->nullable();
            $table->string('transferred_to')->nullable();
            $table->date('date_received')->nullable();
            $table->date('date_tested')->nullable();
            $table->string('result')->nullable();
            $table->string('result_others_remarks')->nullable();
            $table->date('result_date')->nullable();
            $table->text('interpretation')->nullable();
            $table->text('lab_remarks')->nullable();
            $table->text('remarks')->nullable();
            $table->text('hash_qr')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('facility_id')->nullable()->constrained('doh_facilities')->onDelete('cascade');
            
            $table->integer('morbidity_week');
            $table->integer('morbidity_month');
            $table->integer('year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('syndromic_lab_results');
    }
}
