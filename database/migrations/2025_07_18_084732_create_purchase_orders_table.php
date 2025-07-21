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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->string('process_by', 255);
            $table->string('product_name', 255);
            $table->integer('quantity')->default(0)->nullable();
            $table->string('unit', 255)->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
            $table->foreign('approved_by')->references('id')->on('employees')->onDelete('set null');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
