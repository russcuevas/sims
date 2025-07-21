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
        Schema::create('history_return_items', function (Blueprint $table) {
            $table->id();
            $table->string('transact_id', 255)->nullable();
            $table->date('transaction_date')->nullable();
            $table->string('process_by', 255)->nullable();
            $table->unsignedBigInteger('picked_up_by')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->string('product')->nullable();
            $table->string('quantity', 255)->nullable();
            $table->string('unit', 255)->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->integer('is_archived')->default(0);
            $table->foreign('picked_up_by')->references('id')->on('employees')->onDelete('set null');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_return_items');
    }
};
