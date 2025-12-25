<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlsMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bls_members', function (Blueprint $table) {
            $table->id();
            $table->string('enabled', 1)->default('Y');
            $table->string('cho_employee', 1)->default('N');
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('cascade');
            
            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();

            $table->string('provider_type');
            $table->string('position');
            $table->string('institution');
            $table->string('employee_type');
            $table->date('bdate')->nullable();
            $table->string('gender', 1);

            $table->text('street_purok')->nullable();
            $table->foreignId('address_brgy_code')->constrained('edcs_brgies')->onDelete('cascade');
            $table->string('email')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('codename')->nullable();
            
            $table->string('bls_id_number')->nullable();
            $table->string('sfa_id_number')->nullable();

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
        Schema::dropIfExists('bls_members');
    }
}
