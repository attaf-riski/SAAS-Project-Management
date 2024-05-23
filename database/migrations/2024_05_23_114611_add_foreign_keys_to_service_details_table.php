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
        Schema::table('service_details', function (Blueprint $table) {
            $table->foreign(['id_service'])->references(['id'])->on('services')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_details', function (Blueprint $table) {
            $table->dropForeign('service_details_id_service_foreign');
        });
    }
};
