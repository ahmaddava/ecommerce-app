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
        Schema::create('product_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('size'); // S, M, L, XL, XXL
            $table->integer('stock')->default(0);
            $table->decimal('length', 8, 2)->nullable(); // Panjang dalam cm
            $table->decimal('width', 8, 2)->nullable(); // Lebar dalam cm
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            // Unique constraint untuk product_id + size
            $table->unique(['product_id', 'size']);

            // Index untuk query performance
            $table->index(['product_id', 'is_available']);
            $table->index(['size', 'stock']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sizes');
    }
};
