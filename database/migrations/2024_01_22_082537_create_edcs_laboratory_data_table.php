<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEdcsLaboratoryDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edcs_laboratory_data', function (Blueprint $table) {
            $table->id();
            $table->string('lab_id');
            $table->string('case_id');
            $table->string('case_code');
            $table->string('epi_id');
            $table->string('sent_to_ritm', 5);
            
            $table->date('specimen_collected_date');
            $table->string('specimen_type');
            $table->date('date_sent')->nullable();
            $table->date('date_received')->nullable();
            $table->string('result')->nullable();
            $table->text('test_type')->nullable();
            $table->text('interpretation')->nullable();

            $table->text('user_id');
            $table->date('timestamp');
            $table->text('last_modified_by');
            $table->date('last_modified_date');

            $table->text('user_regcode');
            $table->text('user_provcode');
            $table->text('user_citycode');
            $table->text('hfhudcode')->nullable();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edcs_laboratory_data');
    }
}
