<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePregnancyTrackingFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pregnancy_tracking_forms', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('enabled')->default(1);
            $table->string('catchment_brgy');
            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->date('bdate')->nullable();
            $table->integer('age')->nullable();
            $table->string('street_purok')->nullable();
            $table->date('lmp')->nullable();
            $table->date('edc')->nullable();

            $table->string('contact_number')->nullable();

            $table->date('pc_done1')->nullable();
            $table->date('pc_done2')->nullable();
            $table->date('pc_done3')->nullable();
            $table->date('pc_done4')->nullable();

            $table->string('wht_in_charge')->nullable();
            $table->string('midwife_name')->nullable();
            $table->string('duty_station')->nullable();
            $table->string('address1')->nullable();

            $table->string('referral_unit')->nullable();
            $table->string('address2')->nullable();
            $table->string('address3')->nullable();

            $table->string('outcome')->nullable();
            $table->string('accomplished_by')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('pregnancy_tracking_forms');
    }
}
