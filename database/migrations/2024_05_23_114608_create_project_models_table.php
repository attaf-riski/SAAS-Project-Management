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
        Schema::create('project_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('project_name', 100);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status', 100);
            $table->unsignedBigInteger('id_client')->index('project_models_id_client_foreign');
            $table->unsignedBigInteger('user_id')->index('project_models_user_id_foreign');
            $table->string('notes')->nullable();
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
        Schema::dropIfExists('project_models');
    }
};
