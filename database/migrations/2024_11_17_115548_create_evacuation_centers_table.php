<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvacuationCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evacuation_centers', function (Blueprint $table) {
            $table->id();
            $table->string('enabled', 1)->default('Y');
            $table->foreignId('disaster_id')->constrained('disasters')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('address_brgy_code')->constrained('edcs_brgies')->onDelete('cascade');
            $table->string('longlat')->nullable();
            $table->string('status')->default('ACTIVE'); //ACTIVE, DONE
            $table->dateTime('date_start');
            $table->dateTime('date_end')->nullable();
            
            $table->string('has_electricity', 1)->default('Y');
            $table->string('has_water', 1)->default('Y');
            $table->string('has_communication', 1)->default('Y');
            $table->string('has_internet', 1)->default('Y');

            $table->string('rcho_functional', 1)->default('Y');
            $table->string('bhs_functional', 1)->default('Y');

            $table->string('has_flood', 1)->default('Y');
            $table->string('has_landslide', 1)->default('Y');
            $table->string('weather')->nullable();
            $table->string('roads_passable', 1)->default('Y');

            $table->text('remarks')->nullable();

            $table->text('hash');
            $table->timestamps();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('evacuation_centers');
    }
}
