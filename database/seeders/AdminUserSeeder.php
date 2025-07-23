<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@ecommerce.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1, Jakarta'
        ]);

        // Create sample customer
        User::create([
            'name' => 'Customer Test',
            'email' => 'customer@test.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '081234567891',
            'address' => 'Jl. Customer No. 1, Jakarta'
        ]);

        // Create sample categories
        $categories = [
            ['name' => 'Elektronik', 'description' => 'Produk elektronik dan gadget'],
            ['name' => 'Fashion', 'description' => 'Pakaian dan aksesoris'],
            ['name' => 'Rumah Tangga', 'description' => 'Peralatan rumah tangga'],
            ['name' => 'Olahraga', 'description' => 'Peralatan dan perlengkapan olahraga'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create sample products
        $products = [
            [
                'name' => 'Smartphone Android',
                'description' => 'Smartphone Android terbaru dengan kamera 48MP',
                'price' => 2500000,
                'stock' => 50,
                'category_id' => 1,
                'sku' => 'PHONE001',
                'weight' => 0.2
            ],
            [
                'name' => 'Laptop Gaming',
                'description' => 'Laptop gaming dengan processor Intel i7 dan VGA RTX',
                'price' => 15000000,
                'stock' => 20,
                'category_id' => 1,
                'sku' => 'LAPTOP001',
                'weight' => 2.5
            ],
            [
                'name' => 'Kaos Polo',
                'description' => 'Kaos polo premium dengan bahan cotton combed',
                'price' => 150000,
                'stock' => 100,
                'category_id' => 2,
                'sku' => 'POLO001',
                'weight' => 0.3
            ],
            [
                'name' => 'Sepatu Sneakers',
                'description' => 'Sepatu sneakers casual untuk pria dan wanita',
                'price' => 500000,
                'stock' => 75,
                'category_id' => 2,
                'sku' => 'SHOES001',
                'weight' => 0.8
            ],
            [
                'name' => 'Rice Cooker',
                'description' => 'Rice cooker digital dengan kapasitas 1.8 liter',
                'price' => 800000,
                'stock' => 30,
                'category_id' => 3,
                'sku' => 'RICE001',
                'weight' => 3.0
            ],
            [
                'name' => 'Matras Yoga',
                'description' => 'Matras yoga anti slip dengan ketebalan 6mm',
                'price' => 200000,
                'stock' => 40,
                'category_id' => 4,
                'sku' => 'YOGA001',
                'weight' => 1.2
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
