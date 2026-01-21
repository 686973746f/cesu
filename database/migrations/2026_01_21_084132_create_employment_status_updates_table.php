<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmploymentStatusUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employment_status_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('null');

            $table->string('update_type'); //CHANGE, PROMOTION, RESIGNED, RETIRED, END OF CONTRACT, TERMINATED, REHIRED
            $table->date('effective_date');
            $table->text('resigned_remarks')->nullable();
            $table->text('terminated_remarks')->nullable();

            $table->string('job_type')->nullable(); //REGULAR, CASUAL, JO
            $table->string('job_position')->nullable(); //NAME OF ITEM
            $table->string('office')->nullable(); //KUNG SAAN NAKA-ASSIGN
            $table->string('sub_office')->nullable();
            $table->string('source')->nullable(); //LGU, DOH, OTHERS
            
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->uuid('request_uuid');
            
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
        Schema::dropIfExists('employment_status_updates');
    }
}
