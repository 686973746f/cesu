<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecondaryTertiaryRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secondary_tertiary_records', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('morbidityMonth');
            $table->date('dateReported');
            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('gender');
            $table->date('bdate')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('address_houseno')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_brgy')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_cityjson')->nullable();
            $table->string('address_province')->nullable();
            $table->string('address_provincejson')->nullable();
            $table->double('temperature')->nullable();
            $table->tinyInteger('is_primarycc')->default(0);
            $table->tinyInteger('is_secondarycc')->default(0);
            $table->tinyInteger('is_tertiarycc')->default(0);
            $table->date('is_primarycc_date')->nullable();
            $table->date('is_secondarycc_date')->nullable();
            $table->date('is_tertiarycc_date')->nullable();
            $table->dateTime('is_primarycc_date_set')->nullable();
            $table->dateTime('is_secondarycc_date_set')->nullable();
            $table->dateTime('is_tertiarycc_date_set')->nullable();
            $table->text('remarks')->nullable();
            $table->text('from_establishment')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('secondary_tertiary_records');
    }
}
