<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabResultLogBookGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_result_log_book_groups', function (Blueprint $table) {
            $table->id();
            $table->string('disease_tag');
            $table->string('title');
            
            $table->string('base_specimen_type')->nullable();
            $table->string('base_test_type')->nullable();
            $table->string('base_collector_name')->nullable();

            $table->string('sent_to_ritm', 1);
            
            $table->date('ritm_date_sent')->nullable();
            $table->date('ritm_date_received')->nullable();
            $table->string('ritm_received_by')->nullable();

            $table->date('date_sent_others')->nullable();
            $table->date('date_received_others')->nullable();
            $table->string('facility_name_others')->nullable();

            $table->string('driver_name')->nullable();
            
            $table->date('case_open_date');
            $table->date('case_close_date')->nullable();
            $table->string('is_finished', 1)->default('N');
            $table->foreignId('case_closed_by')->nullable()->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('lab_result_log_book_groups');
    }
}
