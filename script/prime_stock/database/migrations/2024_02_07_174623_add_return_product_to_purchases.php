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
        Schema::table('purchases', function (Blueprint $table) {
            $table->mediumText('purchase_note')->nullable()->after('paying_date');
            $table->mediumText('return_note')->nullable()->after('purchase_note');
            $table->integer('return_product')->default(0)->after('return_note');
            $table->decimal('return_discount')->default(0)->after('return_product');
            $table->decimal('return_amount')->default(0)->after('return_discount');
            $table->decimal('receivable_amount')->default(0)->after('return_amount');
            $table->decimal('received_amount')->default(0)->after('receivable_amount');
            $table->date('return_date')->nullable()->after('received_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('purchase_note');
            $table->dropColumn('return_note');
            $table->dropColumn('return_product');
            $table->dropColumn('return_discount');
            $table->dropColumn('return_amount');
            $table->dropColumn('receivable_amount');
            $table->dropColumn('received_amount');
            $table->dropColumn('return_date');
        });
    }
};
