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
            $table->foreignId('group_id')->constrained('lab_result_log_book_groups')->onDelete('cascade');
            $table->string('for_case_id')->nullable();
            
            $table->string('lname')->nullable();
            $table->string('fname')->nullable();
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();

            $table->tinyInteger('age')->nullable();
            $table->string('gender', 1)->nullable();

            $table->string('specimen_type')->nullable();
            $table->string('test_type')->nullable();
            $table->dateTime('date_collected');
            $table->string('collector_name')->nullable();
            $table->string('lab_number')->nullable();

            $table->dateTime('date_released')->nullable();
            $table->string('result')->nullable();
            $table->date('result_updated_date')->nullable();
            $table->foreignId('result_updated_by')->nullable()->constrained('users')->onDelete('cascade');

            $table->text('interpretation')->nullable();
            $table->text('remarks')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('facility_id')->nullable()->constrained('doh_facilities')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
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
