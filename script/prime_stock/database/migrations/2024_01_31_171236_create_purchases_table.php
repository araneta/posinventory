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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique()->index();
            $table->unsignedBigInteger('supplier_id');
            $table->date('date');
            $table->unsignedBigInteger('warehouse_id');
            $table->decimal('total_price', 16, 2)->default(0);
            $table->decimal('discount', 16, 2)->default(0);
            $table->decimal('payable_amount', 16, 2)->default(0);
            $table->decimal('paying_amount', 16, 2)->default(0);
            $table->date('paying_date')->nullable();
            $table->enum('status', ['paid','due'])->default('due');
            $table->timestamps();
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('warehouse_id')->references('id')->on('ware_houses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
