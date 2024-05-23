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
        Schema::table('quotations', function (Blueprint $table) {
            $table->foreign(['id_client'])->references(['id'])->on('clients')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_project'])->references(['id'])->on('project_models')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_user'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropForeign('quotations_id_client_foreign');
            $table->dropForeign('quotations_id_project_foreign');
            $table->dropForeign('quotations_id_user_foreign');
        });
    }
};
