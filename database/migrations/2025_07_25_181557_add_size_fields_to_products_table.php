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
        Schema::table('products', function (Blueprint $table) {
            $table->string('size')->nullable()->after('weight'); // S, M, L, XL, XXL
            $table->decimal('length', 8, 2)->nullable()->after('size'); // Panjang dalam cm
            $table->decimal('width', 8, 2)->nullable()->after('length'); // Lebar dalam cm
            $table->boolean('has_size')->default(false)->after('width'); // Apakah produk memiliki ukuran
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['size', 'length', 'width', 'has_size']);
        });
    }
};
