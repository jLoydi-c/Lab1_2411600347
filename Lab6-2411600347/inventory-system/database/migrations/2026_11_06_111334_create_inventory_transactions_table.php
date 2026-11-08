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
    Schema::create('inventory_transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
        $table->string('type'); // stock_in, stock_out, adjustment
        $table->integer('quantity'); // positive = stock in, negative = stock out
        $table->integer('balance_after')->default(0); // product quantity after this transaction
        $table->string('reference_number')->nullable();
        $table->string('reason')->nullable(); // used by adjustments
        $table->text('notes')->nullable();
        $table->timestamps();

        $table->index(['product_id', 'created_at']);
        $table->index('type');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
