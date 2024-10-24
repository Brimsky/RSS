<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 
    public function up(): void
    {
        // Orders table (high-level order details)
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key to the user
            $table->decimal('total_price', 10, 2); // Total price of the entire order
            $table->string('status')->default('pending'); // Order status (default to 'pending')
            $table->string('payment_method')->nullable(); // Optional: Payment method used
            $table->timestamp('order_date')->useCurrent(); // Date when the order was placed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
