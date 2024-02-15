<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('enabled')->default(1);
            $table->string('name');
            $table->string('email')->unique();
            $table->tinyInteger('isAdmin')->default(0);
            $table->foreignId('brgy_id')->nullable()->constrained('brgy')->onDelete('cascade');
            $table->foreignId('bhs_id')->nullable()->constrained('barangay_health_stations')->onDelete('cascade');
            $table->foreignId('subdivision_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('interviewer_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('pharmacy_branch_id')->nullable()->constrained('pharmacy_branches')->onDelete('cascade');
            $table->foreignId('itr_facility_id')->nullable()->constrained('doh_facilities')->onDelete('cascade');
            $table->foreignId('itr_doctor_id')->nullable()->constrained('syndromic_doctors')->onDelete('cascade');
            $table->tinyInteger('canAccessLinelist')->default(0);
            $table->tinyInteger('canByPassValidation')->default(0);
            $table->tinyInteger('canByPassCutoff')->default(0);
            $table->tinyInteger('canExportReport')->default(0);
            $table->tinyInteger('isValidator')->default(0);
            $table->tinyInteger('isPositiveEncoder')->default(0);
            $table->tinyInteger('canaccess_covid')->default(0);
            $table->tinyInteger('canaccess_abtc')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('option_enableAutoRedirectToCif')->default(0);
            $table->tinyInteger('encoder_stats_visible')->default(0);
            $table->foreignId('abtc_default_vaccinationsite_id')->nullable()->constrained('abtc_vaccination_sites')->onDelete('cascade');
            $table->foreignId('abtc_default_vaccinebrand_id')->nullable()->constrained('abtc_vaccine_brands')->onDelete('cascade');
            $table->date('abtc_default_vaccinebrand_date')->nullable();
            $table->text('permission_list')->nullable();
            $table->timestamp('last_login_date')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        //Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('users');
    }
}
