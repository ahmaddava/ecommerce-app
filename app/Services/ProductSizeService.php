<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductSize;

class ProductSizeService
{
    /**
     * Standar ukuran untuk baju (dalam cm)
     */
    public static function getShirtSizes()
    {
        return [
            'S' => [
                'length' => 65,
                'width' => 48,
                'label' => 'Small (S)',
                'description' => 'Panjang: 65cm, Lebar: 48cm'
            ],
            'M' => [
                'length' => 68,
                'width' => 51,
                'label' => 'Medium (M)',
                'description' => 'Panjang: 68cm, Lebar: 51cm'
            ],
            'L' => [
                'length' => 71,
                'width' => 54,
                'label' => 'Large (L)',
                'description' => 'Panjang: 71cm, Lebar: 54cm'
            ],
            'XL' => [
                'length' => 74,
                'width' => 57,
                'label' => 'Extra Large (XL)',
                'description' => 'Panjang: 74cm, Lebar: 57cm'
            ],
            'XXL' => [
                'length' => 77,
                'width' => 60,
                'label' => 'Double Extra Large (XXL)',
                'description' => 'Panjang: 77cm, Lebar: 60cm'
            ]
        ];
    }

    /**
     * Standar ukuran untuk celana (dalam cm)
     */
    public static function getPantsSizes()
    {
        return [
            'S' => [
                'length' => 95,
                'width' => 30,
                'label' => 'Small (S)',
                'description' => 'Panjang: 95cm, Lebar Pinggang: 30cm'
            ],
            'M' => [
                'length' => 98,
                'width' => 32,
                'label' => 'Medium (M)',
                'description' => 'Panjang: 98cm, Lebar Pinggang: 32cm'
            ],
            'L' => [
                'length' => 101,
                'width' => 34,
                'label' => 'Large (L)',
                'description' => 'Panjang: 101cm, Lebar Pinggang: 34cm'
            ],
            'XL' => [
                'length' => 104,
                'width' => 36,
                'label' => 'Extra Large (XL)',
                'description' => 'Panjang: 104cm, Lebar Pinggang: 36cm'
            ],
            'XXL' => [
                'length' => 107,
                'width' => 38,
                'label' => 'Double Extra Large (XXL)',
                'description' => 'Panjang: 107cm, Lebar Pinggang: 38cm'
            ]
        ];
    }

    /**
     * Mendapatkan ukuran berdasarkan tipe
     */
    public static function getSizesByType($sizeType)
    {
        switch ($sizeType) {
            case 'shirt':
                return self::getShirtSizes();
            case 'pants':
                return self::getPantsSizes();
            default:
                return [];
        }
    }

    /**
     * Mendapatkan ukuran berdasarkan kategori
     */
    public static function getSizesByCategory($categoryName)
    {
        $categoryName = strtolower($categoryName);

        if (str_contains($categoryName, 'baju') || str_contains($categoryName, 'kaos') || str_contains($categoryName, 'kemeja')) {
            return self::getShirtSizes();
        } elseif (str_contains($categoryName, 'celana') || str_contains($categoryName, 'jeans') || str_contains($categoryName, 'pants')) {
            return self::getPantsSizes();
        }

        return [];
    }

    /**
     * Cek apakah kategori memerlukan ukuran
     */
    public static function categoryRequiresSize($categoryName)
    {
        $categoryName = strtolower($categoryName);

        $clothingKeywords = ['baju', 'kaos', 'kemeja', 'celana', 'jeans', 'pants', 'shirt', 'dress'];

        foreach ($clothingKeywords as $keyword) {
            if (str_contains($categoryName, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Mendapatkan tipe ukuran berdasarkan kategori
     */
    public static function getSizeTypeByCategory($categoryName)
    {
        $categoryName = strtolower($categoryName);

        if (str_contains($categoryName, 'baju') || str_contains($categoryName, 'kaos') || str_contains($categoryName, 'kemeja')) {
            return 'shirt';
        } elseif (str_contains($categoryName, 'celana') || str_contains($categoryName, 'jeans') || str_contains($categoryName, 'pants')) {
            return 'pants';
        }

        return null;
    }

    /**
     * Mendapatkan detail ukuran berdasarkan tipe dan size
     */
    public static function getSizeDetails($sizeType, $size)
    {
        $sizes = self::getSizesByType($sizeType);

        if (isset($sizes[$size])) {
            return $sizes[$size];
        }

        return null;
    }

    /**
     * Mendapatkan semua ukuran yang tersedia
     */
    public static function getAllSizes()
    {
        return ['S', 'M', 'L', 'XL', 'XXL'];
    }

    /**
     * Validasi ukuran
     */
    public static function isValidSize($size)
    {
        return in_array($size, self::getAllSizes());
    }

    /**
     * Create product sizes untuk produk baru
     */
    public static function createProductSizes(Product $product, array $sizesData)
    {
        $createdSizes = [];

        foreach ($sizesData as $sizeData) {
            if (!self::isValidSize($sizeData['size'])) {
                continue;
            }

            $sizeDetails = self::getSizeDetails($product->size_type, $sizeData['size']);

            $productSize = ProductSize::create([
                'product_id' => $product->id,
                'size' => $sizeData['size'],
                'stock' => $sizeData['stock'] ?? 0,
                'length' => $sizeDetails['length'] ?? null,
                'width' => $sizeDetails['width'] ?? null,
                'is_available' => $sizeData['is_available'] ?? true
            ]);

            $createdSizes[] = $productSize;
        }

        return $createdSizes;
    }

    /**
     * Update product sizes
     */
    public static function updateProductSizes(Product $product, array $sizesData)
    {
        // Delete existing sizes
        $product->sizes()->delete();

        // Create new sizes
        return self::createProductSizes($product, $sizesData);
    }

    /**
     * Get default sizes untuk tipe tertentu
     */
    public static function getDefaultSizesForType($sizeType)
    {
        $sizes = self::getSizesByType($sizeType);
        $defaultSizes = [];

        foreach ($sizes as $size => $details) {
            $defaultSizes[] = [
                'size' => $size,
                'stock' => 0,
                'is_available' => true,
                'details' => $details
            ];
        }

        return $defaultSizes;
    }

    /**
     * Calculate total stock untuk produk
     */
    public static function calculateTotalStock(Product $product)
    {
        if (!$product->has_sizes) {
            return $product->stock ?? 0;
        }

        return $product->sizes()->sum('stock');
    }

    /**
     * Check if product has any available sizes
     */
    public static function hasAvailableSizes(Product $product)
    {
        if (!$product->has_sizes) {
            return $product->is_active && $product->stock > 0;
        }

        return $product->sizes()->available()->exists();
    }

    /**
     * Get size availability summary
     */
    public static function getSizeAvailabilitySummary(Product $product)
    {
        if (!$product->has_sizes) {
            return [
                'has_sizes' => false,
                'total_stock' => $product->stock ?? 0,
                'available' => $product->is_active && $product->stock > 0
            ];
        }

        $sizes = $product->sizes()->get();
        $totalStock = $sizes->sum('stock');
        $availableSizes = $sizes->where('is_available', true)->where('stock', '>', 0);

        return [
            'has_sizes' => true,
            'size_type' => $product->size_type,
            'total_stock' => $totalStock,
            'available_sizes_count' => $availableSizes->count(),
            'total_sizes_count' => $sizes->count(),
            'available' => $availableSizes->count() > 0,
            'sizes' => $sizes->map(function ($size) {
                return [
                    'size' => $size->size,
                    'stock' => $size->stock,
                    'available' => $size->isInStock(),
                    'status' => $size->stock_status
                ];
            })->toArray()
        ];
    }
}
