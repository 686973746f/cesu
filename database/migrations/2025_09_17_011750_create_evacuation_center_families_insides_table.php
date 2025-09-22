<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvacuationCenterFamiliesInsidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evacuation_center_families_insides', function (Blueprint $table) {
            $table->id();

            $table->dateTime('date_registered');
            $table->foreignId('evacuation_center_id')->constrained('evacuation_centers')->onDelete('cascade');
            $table->foreignId('familyhead_id')->constrained('evacuation_center_family_heads')->onDelete('cascade');
            
            $table->string('family_status'); //ACTIVE, WENT HOME
            $table->date('date_returnedhome')->nullable();
            $table->string('outcome'); //ALIVE, DIED, MISSING
            $table->dateTime('date_missing')->nullable();
            $table->dateTime('date_returned')->nullable();
            $table->dateTime('date_died')->nullable();
            $table->string('is_injured', 1)->default('N');

            $table->string('shelterdamage_classification');
            $table->text('remarks')->nullable();

            $table->string('focal_name');
            $table->string('supervisor_name')->nullable();

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
        Schema::dropIfExists('evacuation_center_families_insides');
    }
}
