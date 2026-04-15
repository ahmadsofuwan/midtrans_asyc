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
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->dateTime('date_created')->nullable();
            $table->string('order_id')->unique();
            $table->string('transaction_type')->nullable();
            $table->string('channel')->nullable();
            $table->string('status')->nullable();
            $table->string('reference_id')->nullable();
            $table->bigInteger('amount')->default(0);
            $table->bigInteger('total_fee')->default(0);
            $table->text('notes')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_mobile')->nullable();
            $table->text('shipping_address')->nullable();
            $table->dateTime('transaction_time')->nullable();
            $table->dateTime('settlement_time')->nullable();
            $table->dateTime('expiry_time')->nullable();
            $table->string('custom_field_1')->nullable();
            $table->string('custom_field_2')->nullable();
            $table->string('custom_field_3')->nullable();
            $table->string('pop_name')->nullable();
            $table->string('payment_provider_ref_id')->nullable();
            $table->string('invoice_id')->nullable();
            $table->string('subscription_id')->nullable();
            $table->string('receiver_account_number')->nullable();
            $table->string('sender')->nullable();
            $table->string('receiver')->nullable();
            $table->dateTime('settlement_date')->nullable();
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
