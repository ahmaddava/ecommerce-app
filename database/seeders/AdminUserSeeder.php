<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSize; // Tambahkan ini jika Anda akan mengisi ProductSize
use App\Models\ProductImage; // Tambahkan ini jika Anda akan mengisi ProductImage
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
            ['name' => 'Baju', 'description' => 'Berbagai jenis atasan dan pakaian atas'],
            ['name' => 'Celana', 'description' => 'Berbagai jenis celana dan bawahan'],
            ['name' => 'Aksesoris', 'description' => 'Pelengkap gaya dan kebutuhan sehari-hari'],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Get category IDs
        $bajuCategory = Category::where('name', 'Baju')->first();
        $celanaCategory = Category::where('name', 'Celana')->first();
        $aksesorisCategory = Category::where('name', 'Aksesoris')->first();

        // Create sample products
        $productsData = [
            [
                'name' => 'Kaos Polos Cotton Combed 30s',
                'description' => 'Kaos polos basic dengan bahan cotton combed 30s yang nyaman dan adem.',
                'price' => 75000,
                'stock' => 120,
                'image' => 'https://placehold.co/600x400/E0E0E0/333333?text=Kaos+Polos',
                'has_multiple_images' => false,
                'category_id' => $bajuCategory->id,
                'sku' => 'BJU-KP001',
                'weight' => 0.2,
                'has_sizes' => false, // Produk ini tanpa ukuran spesifik di model Product, stock diatur di sini
                'size_type' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Kemeja Batik Modern Lengan Panjang',
                'description' => 'Kemeja batik modern dengan motif kontemporer, cocok untuk acara formal maupun kasual.',
                'price' => 250000,
                'stock' => 0, // Stock akan diatur di ProductSize
                'image' => null, // Akan menggunakan multiple images
                'has_multiple_images' => true,
                'category_id' => $bajuCategory->id,
                'sku' => 'BJU-KB002',
                'weight' => 0.4,
                'has_sizes' => true,
                'size_type' => 'shirt',
                'is_active' => true,
                'sizes' => [ // Data untuk ProductSize
                    ['size' => 'S', 'stock' => 15],
                    ['size' => 'M', 'stock' => 25],
                    ['size' => 'L', 'stock' => 20],
                    ['size' => 'XL', 'stock' => 10],
                ],
                'images_data' => [ // Data untuk ProductImage
                    // Mengubah 'type' menjadi 'image_type' dan 'order' menjadi 'sort_order'
                    ['image_path' => 'https://placehold.co/600x400/FFD700/333333?text=Kemeja+Batik+Depan', 'image_type' => 'product', 'is_primary' => true, 'sort_order' => 1, 'alt_text' => 'Kemeja Batik Depan'],
                    ['image_path' => 'https://placehold.co/600x400/FFD700/333333?text=Kemeja+Batik+Belakang', 'image_type' => 'product', 'is_primary' => false, 'sort_order' => 2, 'alt_text' => 'Kemeja Batik Belakang'],
                    ['image_path' => 'https://placehold.co/600x400/FFD700/333333?text=Kemeja+Batik+Detail', 'image_type' => 'detail', 'is_primary' => false, 'sort_order' => 3, 'alt_text' => 'Detail Kemeja Batik'],
                    ['image_path' => 'https://placehold.co/600x400/FFD700/333333?text=Size+Chart+Shirt', 'image_type' => 'size_chart', 'is_primary' => false, 'sort_order' => 4, 'alt_text' => 'Size Chart Kemeja'],
                ]
            ],
            [
                'name' => 'Celana Jeans Slim Fit Pria',
                'description' => 'Celana jeans dengan potongan slim fit, bahan denim berkualitas tinggi.',
                'price' => 350000,
                'stock' => 0, // Stock akan diatur di ProductSize
                'image' => 'https://placehold.co/600x400/4682B4/FFFFFF?text=Celana+Jeans',
                'has_multiple_images' => false,
                'category_id' => $celanaCategory->id,
                'sku' => 'CLN-JNS001',
                'weight' => 0.7,
                'has_sizes' => true,
                'size_type' => 'pants',
                'is_active' => true,
                'sizes' => [ // Data untuk ProductSize
                    ['size' => '28', 'stock' => 10],
                    ['size' => '30', 'stock' => 20],
                    ['size' => '32', 'stock' => 25],
                    ['size' => '34', 'stock' => 15],
                    ['size' => '36', 'stock' => 5],
                ]
            ],
            [
                'name' => 'Celana Chino Casual',
                'description' => 'Celana chino bahan katun stretch, nyaman untuk aktivitas sehari-hari.',
                'price' => 200000,
                'stock' => 80,
                'image' => 'https://placehold.co/600x400/8B4513/FFFFFF?text=Celana+Chino',
                'has_multiple_images' => false,
                'category_id' => $celanaCategory->id,
                'sku' => 'CLN-CH002',
                'weight' => 0.5,
                'has_sizes' => false,
                'size_type' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Topi Baseball Adjustable',
                'description' => 'Topi baseball dengan strap adjustable, cocok untuk berbagai ukuran kepala.',
                'price' => 50000,
                'stock' => 100,
                'image' => 'https://placehold.co/600x400/6A5ACD/FFFFFF?text=Topi+Baseball',
                'has_multiple_images' => false,
                'category_id' => $aksesorisCategory->id,
                'sku' => 'AKS-TP001',
                'weight' => 0.1,
                'has_sizes' => false,
                'size_type' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Jam Tangan Analog Klasik',
                'description' => 'Jam tangan analog dengan desain klasik dan strap kulit sintetis.',
                'price' => 180000,
                'stock' => 45,
                'image' => 'https://placehold.co/600x400/808080/FFFFFF?text=Jam+Tangan',
                'has_multiple_images' => false,
                'category_id' => $aksesorisCategory->id,
                'sku' => 'AKS-JT002',
                'weight' => 0.05,
                'has_sizes' => false,
                'size_type' => null,
                'is_active' => true,
            ],
        ];

        foreach ($productsData as $productData) {
            $sizes = $productData['sizes'] ?? [];
            $imagesData = $productData['images_data'] ?? [];

            // Hapus keys 'sizes' dan 'images_data' sebelum membuat produk
            unset($productData['sizes']);
            unset($productData['images_data']);

            $product = Product::create($productData);

            // Buat ProductSize jika ada
            if (!empty($sizes)) {
                foreach ($sizes as $sizeData) {
                    ProductSize::create([
                        'product_id' => $product->id,
                        'size' => $sizeData['size'],
                        'stock' => $sizeData['stock'],
                        'is_available' => true
                    ]);
                }
            }

            // Buat ProductImage jika ada
            if (!empty($imagesData)) {
                foreach ($imagesData as $imageData) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imageData['image_path'],
                        'image_type' => $imageData['image_type'],
                        'is_primary' => $imageData['is_primary'],
                        'sort_order' => $imageData['sort_order'],
                        'alt_text' => $imageData['alt_text'] ?? null,
                    ]);
                }
            }
        }
    }
}
