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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('quantity')->default(0)->after('alert_quantity');
            $table->decimal('price')->default(0)->after('quantity');
            $table->decimal('sale_price')->default(0)->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->dropColumn('price');
            $table->dropColumn('sale_price');
        });
    }
};
