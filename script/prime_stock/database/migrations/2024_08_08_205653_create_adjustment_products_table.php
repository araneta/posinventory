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
        Schema::create('adjustment_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('adjustment_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->enum('type', ['add', 'subtract']);            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjustment_products');
    }
};
