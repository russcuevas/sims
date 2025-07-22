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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date')->nullable();
            $table->string('process_by', 255)->nullable();
            $table->string('transaction_type', 255)->nullable();
            $table->string('transaction_id', 255)->nullable();
            $table->decimal('debit', 10, 2);
            $table->decimal('credit', 10, 2);
            $table->decimal('balances', 10, 2);
            $table->integer('is_archived')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
