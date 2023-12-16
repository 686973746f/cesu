<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangayHealthStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barangay_health_stations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brgy_id')->constrained('brgy')->onDelete('cascade');
            $table->string('name');
            $table->string('assigned_personnel_name')->nullable();
            $table->string('assigned_personnel_position')->nullable();
            $table->string('assigned_personnel_contact_number')->nullable();

            $table->string('sys_code1', 10)->nullable();
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
        Schema::dropIfExists('barangay_health_stations');
    }
}
