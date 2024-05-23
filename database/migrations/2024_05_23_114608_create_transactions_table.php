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
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_project')->index('transactions_id_project_foreign');
            $table->unsignedBigInteger('id_invoice')->nullable()->index('transactions_id_invoice_foreign');
            $table->unsignedBigInteger('id_payment')->nullable()->index('transactions_id_payment_foreign');
            $table->unsignedBigInteger('id_user')->index('transactions_id_user_foreign');
            $table->date('created_date');
            $table->boolean('is_income');
            $table->string('source');
            $table->string('description');
            $table->string('category');
            $table->bigInteger('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
