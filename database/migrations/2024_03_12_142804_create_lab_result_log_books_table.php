<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabResultLogBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_result_log_books', function (Blueprint $table) {
            $table->id();
            $table->string('for_case_id')->nullable();
            $table->string('disease_tag');

            $table->string('lname')->nullable();
            $table->string('fname')->nullable();
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();

            $table->tinyInteger('age')->nullable();
            $table->string('gender', 1)->nullable();

            $table->date('date_collected');
            $table->string('collector_name')->nullable();
            $table->string('specimen_type')->nullable();
            $table->string('sent_to_ritm', 1);
            $table->date('ritm_date_sent');
            $table->date('ritm_date_received');
            $table->string('driver_name')->nullable();

            $table->string('test_type')->nullable();
            $table->string('result')->nullable();

            $table->text('interpretation')->nullable();
            $table->text('remarks')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('facility_id')->nullable()->constrained('doh_facilities')->onDelete('cascade');
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
        Schema::dropIfExists('lab_result_log_books');
    }
}
