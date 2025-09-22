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

            $table->string('outcome'); //ALIVE, DIED, MISSING
            $table->dateTime('date_missing')->nullable();
            $table->dateTime('date_returned')->nullable();
            $table->dateTime('date_died')->nullable();

            $table->string('is_injured', 1)->default('N');

            $table->text('remarks')->nullable();

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
