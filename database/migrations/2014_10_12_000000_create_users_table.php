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
            $table->tinyInteger('isAdmin')->default(0);
            $table->foreignId('brgy_id')->nullable()->constrained('brgy')->onDelete('cascade');
            $table->foreignId('subdivision_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('interviewer_id')->nullable()->constrained()->onDelete('cascade');
            $table->tinyInteger('canAccessLinelist')->default(0);
            $table->tinyInteger('canByPassValidation')->default(0);
            $table->tinyInteger('canByPassCutoff')->default(0);
            $table->tinyInteger('isValidator')->default(0);
            $table->tinyInteger('isPositiveEncoder')->default(0);
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('option_enableAutoRedirectToCif')->default(0);
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
