<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvacuationCenterFamilyMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evacuation_center_family_members', function (Blueprint $table) {
            $table->id();
            $table->string('enabled', 1)->default('Y');
            $table->foreignId('familyhead_id')->constrained('evacuation_center_family_heads')->onDelete('cascade');
            $table->string('relationship_tohead');

            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->string('nickname')->nullable();
            $table->date('bdate');
            $table->string('sex', 1);
            $table->string('is_pregnant', 1)->default('N');
            $table->string('is_lactating', 1)->default('N');

            $table->string('highest_education')->nullable();
            $table->string('occupation')->nullable();
            //$table->string('cs');
            //$table->string('religion')->nullable();
            
            $table->string('is_pwd', 1)->default('N');
            $table->string('is_4ps', 1)->default('N');
            $table->string('is_indg', 1)->default('N');
            $table->string('indg_specify')->nullable();

            //$table->string('cswd_serialno')->nullable();
            //$table->string('dswd_serialno')->nullable();

            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->text('hash');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evacuation_center_family_members');
    }
}
