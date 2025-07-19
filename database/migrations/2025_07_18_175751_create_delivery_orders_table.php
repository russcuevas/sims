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
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->id();
            $table->string('transact_id', 255)->nullable();
            $table->string('memo', 255)->nullable();
            $table->string('upload_image', 255)->nullable();
            $table->text('upload_notes')->nullable();
            $table->date('transaction_date')->nullable();
            $table->date('expected_delivery')->nullable();
            $table->string('process_by', 255)->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('delivered_by')->nullable();
            $table->unsignedBigInteger('car')->nullable();
            $table->unsignedBigInteger('store')->nullable();
            $table->string('product_name', 255)->nullable();
            $table->string('pack', 255)->nullable();
            $table->string('unit', 255)->nullable();
            $table->integer('quantity_ordered')->nullable();
            $table->string('quantity_received', 255)->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->integer('total_ordered')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->string('status', 255)->nullable();
            $table->integer('is_archived')->default(0);

            $table->timestamps();
            $table->foreign('approved_by')->references('id')->on('employees')->onDelete('set null');
            $table->foreign('delivered_by')->references('id')->on('employees')->onDelete('set null');
            $table->foreign('car')->references('id')->on('cars')->onDelete('set null');
            $table->foreign('store')->references('id')->on('stores')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_orders');
    }
};
