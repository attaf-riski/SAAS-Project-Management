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
        Schema::create('tasks_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tasks');
            $table->date('tasks_due_date')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('id_client')->nullable()->index('tasks_clients_id_client_foreign');
            $table->unsignedBigInteger('id_user')->index('tasks_clients_id_user_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks_clients');
    }
};
