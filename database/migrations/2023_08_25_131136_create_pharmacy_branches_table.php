<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmacyBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacy_branches', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('enabled')->default(1);
            $table->string('name');
            $table->text('focal_person')->nullable();
            $table->text('contact_number')->nullable();
            $table->text('description')->nullable();
            $table->string('level')->nullable();
            $table->foreignId('if_bhs_id')->nullable()->constrained('barangay_health_stations')->onDelete('cascade');
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
        Schema::dropIfExists('pharmacy_branches');
    }
}
