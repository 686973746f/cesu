<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialHygieneTclsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_hygiene_tcls', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('month');

            $table->foreignId('address_brgy_code')->constrained('edcs_brgies')->onDelete('cascade');

            $table->integer('r_preg_syphilis')->default(0);
            $table->integer('nr_preg_syphilis')->default(0);
            $table->integer('treated_preg_syphilis')->default(0);
            $table->integer('r_preg_hiv')->default(0);
            $table->integer('nr_preg_hiv')->default(0);
            $table->integer('r_preg_hiv')->default(0);
            
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
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
        Schema::dropIfExists('social_hygiene_tcls');
    }
}
