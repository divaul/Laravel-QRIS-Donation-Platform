<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->string('donor_name');
            $table->text('message')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('payment_type')->default('qris');
            $table->string('transaction_status')->default('pending');
            $table->string('transaction_id')->nullable();
            $table->text('qr_code')->nullable(); // Base64 QRIS code
            $table->string('acquirer')->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->timestamp('settlement_time')->nullable();
            $table->json('raw_notification')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
