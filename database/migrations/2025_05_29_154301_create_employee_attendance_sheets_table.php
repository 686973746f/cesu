<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeAttendanceSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_attendance_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('for_date');

            $table->string('is_travelorder')->default('N');
            $table->string('filed_forleave')->default('N');
            $table->string('leave_type')->nullable();
            $table->text('leave_remarks')->nullable();
            
            $table->string('is_halfday')->default('N');
            $table->time('timein_am')->nullable();
            $table->time('timeout_am')->nullable();
            $table->time('timein_pm')->nullable();
            $table->time('timeout_pm')->nullable();

            $table->text('supervisor_remarks')->nullable();
            $table->text('admin_remarks')->nullable();
            $table->text('depthead_remarks')->nullable();
            $table->text('hr_remarks')->nullable();

            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('employee_attendance_sheets');
    }
}
