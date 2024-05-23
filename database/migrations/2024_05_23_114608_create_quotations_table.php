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
        Schema::create('quotations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('quotation_name', 100);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status', 100);
            $table->string('snap_token')->nullable();
            $table->string('quotation_pdf');
            $table->unsignedBigInteger('id_client')->index('quotations_id_client_foreign');
            $table->unsignedBigInteger('id_user')->index('quotations_id_user_foreign');
            $table->unsignedBigInteger('id_project')->index('quotations_id_project_foreign');
            $table->timestamps();
            $table->boolean('require_deposit')->default(false);
            $table->decimal('deposit_amount', 10)->nullable();
            $table->decimal('deposit_percentage', 10)->nullable();
            $table->boolean('client_agrees_deposit')->default(false);
            $table->enum('invoice_type',array('once','hourly','daily','weekly','monthly','custom'))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
