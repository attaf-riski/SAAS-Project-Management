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
        Schema::table('services', function (Blueprint $table) {
            $table->foreign(['id_contract'])->references(['id'])->on('contracts')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_project'])->references(['id'])->on('project_models')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_quotation'])->references(['id'])->on('quotations')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign('services_id_contract_foreign');
            $table->dropForeign('services_id_project_foreign');
            $table->dropForeign('services_id_quotation_foreign');
        });
    }
};
