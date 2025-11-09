<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvacuationCenterFamilyMembersInsidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evacuation_center_family_members_insides', function (Blueprint $table) {
            $table->id();
            $table->string('enabled', 1)->default('Y');
            $table->dateTime('date_registered');
            $table->foreignId('familyinside_id')->constrained('evacuation_center_families_insides')->onDelete('cascade');
            $table->foreignId('member_id')->constrained('evacuation_center_family_members')->onDelete('cascade');

            $table->string('is_pregnant', 1)->default('N');
            $table->string('is_lactating', 1)->default('N');
            $table->string('is_pwd', 1)->default('N');
            
            $table->string('is_injured', 1)->default('N');
            $table->string('is_admitted', 1)->default('N');
            $table->date('date_admitted')->nullable();
            $table->date('date_discharged')->nullable();
            $table->string('outcome'); //ALIVE, DIED, MISSING, MISSING THEN RETURNED
            $table->dateTime('date_missing')->nullable();
            $table->dateTime('date_returned')->nullable();
            $table->dateTime('date_died')->nullable();

            $table->text('remarks')->nullable();

            $table->integer('age_years')->nullable();
            $table->integer('age_months')->nullable();
            $table->integer('age_days')->nullable();

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
        Schema::dropIfExists('evacuation_center_family_members_insides');
    }
}
