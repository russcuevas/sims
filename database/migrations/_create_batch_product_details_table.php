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
        Schema::create('batch_product_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();

            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name', 255)->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('quantity')->default(0)->nullable();
            $table->string('stock_unit_id', 255)->nullable();
            $table->string('category', 255)->nullable();

            $table->timestamps();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_product_details');
    }
};
