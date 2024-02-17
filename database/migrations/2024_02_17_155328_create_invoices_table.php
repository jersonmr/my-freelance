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
        Schema::disableForeignKeyConstraints();

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('client_id')->nullable()->constrained();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->unsignedBigInteger('payment_gateway_id')->nullable();
            $table->string('number')->unique()->index();
            $table->string('subject');
            $table->timestamp('due')->nullable();
            $table->string('payment_type');
            $table->json('items');
            $table->integer('tax')->nullable();
            $table->integer('subtotal');
            $table->integer('total');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
