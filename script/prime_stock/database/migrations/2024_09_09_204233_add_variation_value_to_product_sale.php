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
        Schema::table('product_sale', function (Blueprint $table) {
            $table->string('variation_id')->nullable()->after('product_id');
            $table->string('variation_value')->nullable()->after('variation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_sale', function (Blueprint $table) {
            $table->dropColumn('variation_id');
            $table->dropColumn('variation_value');
        });
    }
};
