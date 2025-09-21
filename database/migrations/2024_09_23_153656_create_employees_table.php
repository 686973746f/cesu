<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->string('profession_suffix')->nullable();
            $table->string('gender');
            $table->date('bdate')->nullable();

            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();

            $table->text('address_region_code')->nullable();
            $table->text('address_region_text')->nullable();
            $table->text('address_province_code')->nullable();
            $table->text('address_province_text')->nullable();
            $table->text('address_muncity_code')->nullable();
            $table->text('address_muncity_text')->nullable();
            $table->text('address_brgy_code')->nullable();
            $table->text('address_brgy_text')->nullable();
            $table->text('address_street')->nullable();
            $table->text('address_houseno')->nullable();

            $table->string('weight_kg')->nullable();
            $table->string('height_cm')->nullable();
            $table->string('shirt_size')->nullable(); //S,M,L,XL,XXL,XXXL

            $table->string('type'); //REGULAR, CASUAL, JO
            $table->string('job_position')->nullable(); //NAME OF ITEM
            $table->string('office')->nullable(); //KUNG SAAN NAKA-ASSIGN
            $table->string('sub_office')->nullable();
            $table->date('date_hired')->nullable();
            $table->string('source')->nullable(); //LGU, DOH, OTHERS
            $table->string('employment_status'); //ACTIVE, RESIGNED, RETIRED
            $table->date('date_resigned')->nullable();
            $table->string('remarks')->nullable();

            $table->text('picture_file')->nullable();
            $table->text('fingerprint_hash')->nullable();

            $table->string('is_blstrained', 1)->default('N');
            $table->date('recent_bls_date')->nullable();
            $table->string('bls_id')->nullable();
            $table->string('bls_typeofrescuer')->nullable();
            $table->string('bls_codename')->nullable();
            $table->string('duty_canbedeployed', 1);
            $table->string('duty_team')->nullable();
            $table->string('duty_completedcycle', 1)->default('N');
            $table->string('duty_canbedeployedagain', 1)->default('N');
            $table->integer('duty_balance')->default(0);

            $table->string('prc_license_no')->nullable();
            $table->string('tin_no')->nullable();
            $table->string('philhealth_pan')->nullable();

            $table->foreignId('abtc_vaccinator_branch')->nullable()->constrained('users')->onDelete('cascade');

            $table->text('emp_access_list')->nullable();
            $table->foreignId('systemuser_id')->nullable()->constrained('users')->onDelete('cascade');
            
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
        Schema::dropIfExists('employees');
    }
}
