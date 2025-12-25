<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlsBatchParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bls_batch_participants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('batch_id')->nullable()->constrained('bls_mains')->onDelete('cascade');
            $table->foreignId('member_id')->nullable()->constrained('bls_members')->onDelete('cascade');
            $table->integer('sfa_pretest')->nullable();
            $table->integer('sfa_posttest')->nullable();
            $table->integer('sfa_remedial')->nullable();
            $table->string('sfa_ispassed', 1)->default('F');
            $table->text('sfa_notes')->nullable();

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
            $table->text('bls_notes')->nullable();
            
            $table->text('bls_certificate_link')->nullable();
            $table->text('sfa_certificate_link')->nullable();
            
            $table->date('bls_expiration_date')->nullable();
            $table->text('picture')->nullable();

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
        Schema::dropIfExists('bls_batch_participants');
    }
}
