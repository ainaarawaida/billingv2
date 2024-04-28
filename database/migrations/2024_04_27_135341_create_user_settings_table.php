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
        Schema::dropIfExists('user_settings');
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->string('quotation_prefix_code')->nullable();
            $table->string('quotation_current_no')->nullable();
            $table->string('quotation_template')->nullable();

            $table->string('invoice_prefix_code')->nullable();
            $table->string('invoice_current_no')->nullable();
            $table->string('invoice_template')->nullable();
            $table->json('payment_gateway')->nullable();
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
