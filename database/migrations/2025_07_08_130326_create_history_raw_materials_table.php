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
        Schema::create('history_raw_materials', function (Blueprint $table) {
            $table->id();
            $table->string('transact_id', 255)->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('unit', 255)->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->string('process_by', 255)->nullable();
            $table->date('received_date')->nullable();
            $table->integer('is_archived')->default(0);

            $table->timestamps();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_raw_materials');
    }
};
