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
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign(['id_invoice'])->references(['id'])->on('invoices')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_payment'])->references(['id'])->on('payments')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_project'])->references(['id'])->on('project_models')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_user'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign('transactions_id_invoice_foreign');
            $table->dropForeign('transactions_id_payment_foreign');
            $table->dropForeign('transactions_id_project_foreign');
            $table->dropForeign('transactions_id_user_foreign');
        });
    }
};
