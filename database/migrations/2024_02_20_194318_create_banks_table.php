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

        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('location');
            $table->string('name')->index();
            $table->string('swift');
            $table->string('iban')->unique();
            $table->string('address');
            $table->string('beneficiary_name');
            $table->string('beneficiary_address');
            $table->string('beneficiary_email')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
