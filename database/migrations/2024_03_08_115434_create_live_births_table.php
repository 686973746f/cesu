<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveBirthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_births', function (Blueprint $table) {
            $table->id();
            $table->string('registryno')->nullable();
            $table->string('year');
            $table->string('month');

            $table->string('lname')->nullable();
            $table->string('fname')->nullable();
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->string('sex');
            $table->date('dob');

            $table->string('parent_lname')->nullable();
            $table->string('parent_fname')->nullable();
            $table->string('parent_mname')->nullable();
            $table->string('parent_suffix')->nullable();

            $table->text('address_region_code');
            $table->text('address_region_text');
            $table->text('address_province_code');
            $table->text('address_province_text');
            $table->text('address_muncity_code');
            $table->text('address_muncity_text');
            $table->text('address_brgy_code');
            $table->text('address_brgy_text');
            $table->text('street_purok')->nullable();
            //$table->text('address_houseno')->nullable();
            
            $table->string('hospital_lyingin')->nullable();
            $table->integer('mother_age');
            $table->string('mode_delivery')->nullable();
            $table->string('multiple_delivery');

            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('live_births');
    }
}
