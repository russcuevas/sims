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
        Schema::create('sales_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_date', 255)->nullable();
            $table->string('process_by')->nullable();
            $table->string('transaction_type')->nullable();
            $table->string('transaction_id', 255)->nullable();
            $table->string('payment', 255)->nullable();
            $table->string('return', 255)->nullable();
            $table->string('debit', 255)->nullable();
            $table->string('credit', 255)->nullable();
            $table->string('loss', 255)->nullable();
            $table->string('balances', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_transactions');
    }
};
