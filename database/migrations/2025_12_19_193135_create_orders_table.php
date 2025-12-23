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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            
            $table->string('customer_email');
            $table->string('customer_first_name');
            $table->string('customer_last_name');
            $table->string('customer_phone')->nullable();

            // Invoice Info
            $table->enum('document_type', ['boleta', 'factura'])->default('boleta');

            $table->foreignId('billing_address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->foreignId('shipping_address_id')->nullable()->constrained('addresses')->nullOnDelete();

            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_total', 10, 2);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('discount_total', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2);

            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('coupon_discount', 10, 2)->default(0);

            $table->string('payment_method');
            $table->string('payment_status')->default('pending'); // pending, processing, completed, failed, refunded
            $table->string('payment_transaction_id')->nullable();

            $table->string('shipping_method')->nullable();
            $table->string('tracking_number')->nullable();

            $table->string('current_status')->default('pending'); // pending, processing, shipped, delivered, cancelled, refunded
            $table->text('notes')->nullable();

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