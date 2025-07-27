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
        // Add size column to cart table
        Schema::table('cart', function (Blueprint $table) {
            $table->string('size')->nullable()->after('quantity');
        });

        // Add size column to order_items table
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('size')->nullable()->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove size column from cart table
        Schema::table('cart', function (Blueprint $table) {
            $table->dropColumn('size');
        });

        // Remove size column from order_items table
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('size');
        });
    }
};
