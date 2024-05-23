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
        Schema::table('transaction_admins', function (Blueprint $table) {
            $table->foreign(['id_subscription'])->references(['id'])->on('subscriptions')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_user'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_admins', function (Blueprint $table) {
            $table->dropForeign('transaction_admins_id_subscription_foreign');
            $table->dropForeign('transaction_admins_id_user_foreign');
        });
    }
};
