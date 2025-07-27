<?php

namespace App\Services;

class SizeService
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
     * Mendapatkan detail ukuran berdasarkan kategori dan size
     */
    public static function getSizeDetails($categoryName, $size)
    {
        $sizes = self::getSizesByCategory($categoryName);

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
     * Format ukuran untuk display
     */
    public static function formatSizeForDisplay($categoryName, $size)
    {
        $sizeDetails = self::getSizeDetails($categoryName, $size);

        if ($sizeDetails) {
            return $sizeDetails['label'] . ' - ' . $sizeDetails['description'];
        }

        return $size;
    }
}
