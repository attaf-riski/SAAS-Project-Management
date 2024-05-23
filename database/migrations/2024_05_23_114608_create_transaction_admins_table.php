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
        Schema::create('transaction_admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_subscription')->index('transaction_admins_id_subscription_foreign');
            $table->unsignedBigInteger('id_user')->index('transaction_admins_id_user_foreign');
            $table->decimal('amount');
            $table->date('date');
            $table->string('status')->default('PAID');
            $table->string('snap_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_admins');
    }
};
