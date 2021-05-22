<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('status');
            $table->foreignId('status_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('status_remarks')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->date('bdate');
            $table->string('gender');
            $table->smallInteger('isPregnant'); //only for female
            $table->string('cs');
            $table->string('nationality');
            $table->string('mobile');
            $table->string('phoneno')->nullable();
            $table->string('email')->nullable();
            $table->string('philhealth')->nullable();
            $table->string('address_houseno');
            $table->string('address_street');
            $table->string('address_brgy');
            $table->string('address_city');
            $table->string('address_cityjson');
            $table->string('address_province');
            $table->string('address_provincejson');

            $table->tinyInteger('permaaddressDifferent');
            $table->string('permaaddress_houseno');
            $table->string('permaaddress_street');
            $table->string('permaaddress_brgy');
            $table->string('permaaddress_city');
            $table->string('permaaddress_cityjson');
            $table->string('permaaddress_province');
            $table->string('permaaddress_provincejson');
            $table->string('permamobile')->nullable();
            $table->string('permaphoneno')->nullable();
            $table->string('permaemail')->nullable();

            $table->smallInteger('hasOccupation');
            $table->string('occupation')->nullable();
            $table->enum('worksInClosedSetting', ["YES","NO","UNKNOWN"])->default("NO");
            $table->string('occupation_lotbldg')->nullable();
            $table->string('occupation_street')->nullable();
            $table->string('occupation_brgy')->nullable();
            $table->string('occupation_city')->nullable();
            $table->string('occupation_cityjson')->nullable();
            $table->string('occupation_province')->nullable();
            $table->string('occupation_provincejson')->nullable();
            $table->string('occupation_name')->nullable();
            $table->string('occupation_mobile')->nullable();
            $table->string('occupation_email')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('records');
    }
}
