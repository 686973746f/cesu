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
            $table->string('cho_employee', 1)->default('N');
            $table->foreignId('employee_id')->nullable()->constrained('edcs_brgies')->onDelete('cascade');
            
            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();

            $table->string('provider_type');
            $table->string('position');
            $table->string('institution');
            $table->string('employee_type');
            $table->date('bdate')->nullable();

            $table->text('street_purok')->nullable();
            $table->foreignId('address_brgy_code')->constrained('edcs_brgies')->onDelete('cascade');
            $table->string('email')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('codename')->nullable();

            $table->integer('sfa_pretest')->nullable();
            $table->integer('sfa_posttest')->nullable();
            $table->integer('sfa_remedial')->nullable();
            $table->string('sfa_ispassed', 1)->default('F');

            $table->integer('bls_pretest')->nullable();
            $table->integer('bls_posttest')->nullable();
            $table->integer('bls_remedial')->nullable();
            $table->string('bls_cognitive_ispassed', 1)->default('F');

            $table->integer('bls_cpr_adult')->nullable();
            $table->integer('bls_cpr_infant')->nullable();
            $table->integer('bls_fbao_adult')->nullable();
            $table->integer('bls_fbao_infant')->nullable();
            $table->integer('bls_rb_adult')->nullable();
            $table->integer('bls_rb_infant')->nullable();
            $table->string('bls_psychomotor_ispassed', 1)->default('F');
            $table->integer('bls_affective')->nullable();
            $table->string('bls_finalremarks', 1)->default('F');

            $table->string('bls_id_number')->nullable();
            $table->string('sfa_id_number')->nullable();
            $table->date('bls_expiration_date')->nullable();
            $table->text('picture')->nullable();
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
