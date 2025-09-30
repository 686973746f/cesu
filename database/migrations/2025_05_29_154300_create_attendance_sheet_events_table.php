<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceSheetEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_sheet_events', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('event_type'); //HOLIDAY, SUSPENSION, TRAVELORDER

            $table->string('is_onedayevent', 1)->default('Y');
            $table->date('start_date');
            $table->date('end_date');

            $table->string('for_allemployees', 1)->default('N');
            $table->text('specify_employee_ids')->nullable();

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
        Schema::dropIfExists('attendance_sheet_events');
    }
}
