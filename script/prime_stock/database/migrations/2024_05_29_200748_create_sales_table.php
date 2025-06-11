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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique()->index();
            $table->unsignedBigInteger('customer_id');
            $table->date('date');
            $table->unsignedBigInteger('warehouse_id');
            $table->decimal('total_price', 16, 2)->default(0);
            $table->decimal('discount', 16, 2)->default(0);
            $table->decimal('receivable_amount', 16, 2)->default(0);
            $table->decimal('received_amount', 16, 2)->default(0);
            $table->date('received_date')->nullable();
            $table->enum('status', ['received', 'due'])->default('due');
            $table->mediumText('sale_note')->nullable();
            $table->mediumText('return_note')->nullable();
            $table->integer('return_product')->default(0);
            $table->decimal('return_discount')->default(0);
            $table->decimal('return_amount')->default(0);
            $table->decimal('payable_amount')->default(0);
            $table->decimal('paying_amount')->default(0);
            $table->date('return_date')->nullable();
            $table->date('paying_date')->nullable();
            $table->enum ('paying_status', ['due', 'paid'])->default('due');
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('warehouse_id')->references('id')->on('ware_houses');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
