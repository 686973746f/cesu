<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmacyCartMainBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacy_cart_main_branches', function (Blueprint $table) {
            $table->id();

            $table->string('status')->default('PENDING');
            $table->foreignId('branch_id')->nullable()->constrained('pharmacy_branches')->onDelete('cascade');
            $table->foreignId('processor_branch_id')->nullable()->constrained('pharmacy_branches')->onDelete('cascade');
            $table->text('remarks')->nullable();
            
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->uuid('processed_request_uuid')->nullable()->unique();
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
        Schema::dropIfExists('pharmacy_cart_main_branches');
    }
}
