<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyInventoryTransactionMainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_inventory_transaction_mains', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('APPROVED');
            $table->date('date_prepared');
            $table->string('fund_cluster')->nullable();
            $table->string('ptr_no')->nullable();
            
            $table->string('type'); //ISSUED, RECEIVED, TRANSFERRED
            
            $table->string('transferto_type')->nullable(); //REGISTERED_FACILITY, OTHERS (FREETEXT)
            $table->foreignId('transferto_facility')->nullable()->constrained('doh_facilities')->onDelete('cascade');
            $table->string('transfer_type')->nullable(); //DONATION, REASSIGNMENT, RELOCATE, OTHERS
            $table->text('transferto_others')->nullable();
            $table->text('state_reason')->nullable();

            $table->string('approved_by')->nullable();
            $table->string('approved_position')->nullable();
            $table->string('approved_contact')->nullable();
            $table->date('approved_date')->nullable();
            $table->text('approved_remarks')->nullable();

            $table->string('released_by')->nullable();
            $table->string('released_position')->nullable();
            $table->string('released_contact')->nullable();
            $table->date('released_date')->nullable();
            $table->text('released_remarks')->nullable();

            $table->string('delivered_by')->nullable();
            $table->string('delivered_position')->nullable();
            $table->string('delivered_contact')->nullable();
            $table->date('delivered_date')->nullable();
            $table->text('delivered_remarks')->nullable();

            $table->string('received_by')->nullable();
            $table->string('received_position')->nullable();
            $table->string('received_contact')->nullable();
            $table->date('received_date')->nullable();
            $table->text('received_remarks')->nullable();

            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('facility_id')->constrained('doh_facilities')->onDelete('cascade');
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
        Schema::dropIfExists('property_inventory_transaction_mains');
    }
}
