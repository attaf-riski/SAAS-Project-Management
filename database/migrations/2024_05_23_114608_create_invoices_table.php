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
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_project')->index('invoices_id_project_foreign');
            $table->unsignedBigInteger('id_client')->index('invoices_id_client_foreign');
            $table->date('issued_date');
            $table->string('status');
            $table->string('snap_token')->nullable();
            $table->date('due_date')->nullable();
            $table->unsignedBigInteger('total');
            $table->string('invoice_pdf');
            $table->unsignedBigInteger('user_id')->index('invoices_user_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
