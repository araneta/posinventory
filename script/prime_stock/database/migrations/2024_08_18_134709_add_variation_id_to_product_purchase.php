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
        Schema::table('product_purchase', function (Blueprint $table) {
            $table->unsignedBigInteger('variation_id')->nullable()->after('purchase_id');
            $table->foreign('variation_id')->references('id')->on('variations')->onDelete('cascade');
            $table->string('variation_value')->nullable()->after('variation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_purchase', function (Blueprint $table) {
            $table->dropForeign(['variation_id']);
            $table->dropColumn('variation_id');
            $table->dropColumn('variation_value');
        });
    }
};
