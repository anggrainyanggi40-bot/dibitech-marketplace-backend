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
    Schema::create('payments', function (Blueprint $table) {
        $table->id();

        $table->foreignId('order_id')
            ->unique()
            ->constrained('orders')
            ->onDelete('cascade');

        $table->string('transaction_id')->nullable();

        $table->decimal('amount', 12, 2);

        $table->string('payment_method')->nullable();

        $table->enum('payment_status', [
            'pending',
            'paid',
            'failed',
            'expired'
        ])->default('pending');

        $table->timestamp('payment_date')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
   public function down(): void
{
    Schema::dropIfExists('payments');
}
};
