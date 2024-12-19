<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('enabled', 1)->default('Y');
            $table->string('name');
            $table->string('school_type');
            $table->string('school_id')->nullable();
            $table->foreignId('address_brgy_code')->constrained('edcs_brgies')->onDelete('cascade');
            $table->string('contact_number')->nullable();
            $table->string('contact_number_telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('schoolhead_name')->nullable();
            $table->string('schoolhead_position')->nullable();
            $table->string('focalperson_name')->nullable();
            $table->string('longlat')->nullable();
            $table->string('qr');
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
        Schema::dropIfExists('schools');
    }
}
