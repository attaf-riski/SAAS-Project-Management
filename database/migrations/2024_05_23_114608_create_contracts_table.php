<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('contract_name');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status');
            $table->string('snap_token')->nullable();
            $table->longText('contract_pdf');
            $table->unsignedBigInteger('id_client')->index('contracts_id_client_foreign');
            $table->unsignedBigInteger('id_project')->index('contracts_id_project_foreign');
            $table->unsignedBigInteger('id_user')->index('contracts_id_user_foreign');
            $table->timestamps();
            $table->boolean('require_deposit')->default(false);
            $table->decimal('deposit_amount', 10)->nullable();
            $table->decimal('deposit_percentage', 10)->nullable();
            $table->boolean('client_agrees_deposit')->default(false);
            $table->enum('invoice_type',array('once','hourly','daily','weekly','monthly'))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
