<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamAssistanceRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fam_assistance_records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fhid')->constrained('evacuation_center_family_heads')->onDelete('cascade');
            $table->foreignId('ecid')->constrained('evacuation_centers')->onDelete('cascade');

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
        Schema::dropIfExists('fam_assistance_records');
    }
}
