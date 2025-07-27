<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImageService
{
    /**
     * Upload dan simpan gambar produk
     */
    public static function uploadProductImage(UploadedFile $file, Product $product, $imageType = 'product', $isPrimary = false)
    {
        // Generate unique filename
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = 'products/' . $product->id . '/' . $filename;

        // Upload file
        $uploaded = Storage::disk('public')->put($path, file_get_contents($file));

        if (!$uploaded) {
            throw new \Exception('Failed to upload image');
        }

        // Get next sort order
        $sortOrder = ProductImage::getNextSortOrder($product->id);

        // Create ProductImage record
        $productImage = ProductImage::create([
            'product_id' => $product->id,
            'image_path' => $path,
            'image_type' => $imageType,
            'alt_text' => $product->name . ' - ' . ucfirst($imageType),
            'sort_order' => $sortOrder,
            'is_primary' => $isPrimary
        ]);

        // Set as primary if specified
        if ($isPrimary) {
            $productImage->setAsPrimary();
        }

        return $productImage;
    }

    /**
     * Upload multiple images
     */
    public static function uploadMultipleImages(array $files, Product $product, $imageType = 'product')
    {
        $uploadedImages = [];
        $isPrimary = true; // First image will be primary

        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $productImage = self::uploadProductImage($file, $product, $imageType, $isPrimary);
                $uploadedImages[] = $productImage;
                $isPrimary = false; // Only first image is primary
            }
        }

        return $uploadedImages;
    }

    /**
     * Update product images
     */
    public static function updateProductImages(Product $product, array $newImages, $imageType = 'product')
    {
        // Delete existing images of this type
        $existingImages = $product->images()->type($imageType)->get();
        foreach ($existingImages as $image) {
            $image->delete(); // This will also delete the file
        }

        // Upload new images
        if (!empty($newImages)) {
            return self::uploadMultipleImages($newImages, $product, $imageType);
        }

        return [];
    }

    /**
     * Set primary image
     */
    public static function setPrimaryImage(Product $product, $imageId)
    {
        $image = $product->images()->find($imageId);

        if ($image) {
            $image->setAsPrimary();
            return $image;
        }

        return null;
    }

    /**
     * Delete image
     */
    public static function deleteImage($imageId)
    {
        $image = ProductImage::find($imageId);

        if ($image) {
            $image->delete(); // This will also delete the file
            return true;
        }

        return false;
    }

    /**
     * Reorder images
     */
    public static function reorderImages(Product $product, array $imageOrder)
    {
        foreach ($imageOrder as $index => $imageId) {
            $product->images()->where('id', $imageId)->update([
                'sort_order' => $index + 1
            ]);
        }

        return true;
    }

    /**
     * Get image types
     */
    public static function getImageTypes()
    {
        return [
            'product' => 'Gambar Produk',
            'size_chart' => 'Tabel Ukuran',
            'detail' => 'Detail Produk'
        ];
    }

    /**
     * Validate image file
     */
    public static function validateImageFile(UploadedFile $file)
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \Exception('File type not allowed. Only JPEG, PNG, JPG, GIF, and WebP are allowed.');
        }

        if ($file->getSize() > $maxSize) {
            throw new \Exception('File size too large. Maximum size is 5MB.');
        }

        return true;
    }

    /**
     * Process product images untuk form submission
     */
    public static function processProductImages(Product $product, array $imageData)
    {
        $results = [
            'product' => [],
            'size_chart' => [],
            'detail' => []
        ];

        foreach ($imageData as $type => $files) {
            if (!empty($files)) {
                // Validate files
                foreach ($files as $file) {
                    if ($file instanceof UploadedFile) {
                        self::validateImageFile($file);
                    }
                }

                // Upload images
                $uploadedImages = self::uploadMultipleImages($files, $product, $type);
                $results[$type] = $uploadedImages;
            }
        }

        // Update product flag
        $hasMultipleImages = count($results['product']) > 1 ||
            count($results['size_chart']) > 0 ||
            count($results['detail']) > 0;

        $product->update(['has_multiple_images' => $hasMultipleImages]);

        return $results;
    }

    /**
     * Get product image summary
     */
    public static function getProductImageSummary(Product $product)
    {
        $images = $product->images()->get();

        return [
            'total_images' => $images->count(),
            'product_images' => $images->where('image_type', 'product')->count(),
            'size_chart_images' => $images->where('image_type', 'size_chart')->count(),
            'detail_images' => $images->where('image_type', 'detail')->count(),
            'has_primary' => $images->where('is_primary', true)->count() > 0,
            'primary_image' => $images->where('is_primary', true)->first(),
            'images_by_type' => [
                'product' => $images->where('image_type', 'product')->values(),
                'size_chart' => $images->where('image_type', 'size_chart')->values(),
                'detail' => $images->where('image_type', 'detail')->values()
            ]
        ];
    }

    /**
     * Create default image structure untuk produk baru
     */
    public static function createDefaultImageStructure(Product $product)
    {
        // Jika produk memiliki gambar lama, convert ke ProductImage
        if ($product->image && !$product->has_multiple_images) {
            $productImage = ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $product->image,
                'image_type' => 'product',
                'alt_text' => $product->name,
                'sort_order' => 1,
                'is_primary' => true
            ]);

            $product->update(['has_multiple_images' => true]);

            return $productImage;
        }

        return null;
    }

    /**
     * Cleanup orphaned images
     */
    public static function cleanupOrphanedImages()
    {
        $orphanedImages = ProductImage::whereDoesntHave('product')->get();

        foreach ($orphanedImages as $image) {
            $image->delete();
        }

        return $orphanedImages->count();
    }

    /**
     * Generate image variants (thumbnail, medium, large)
     */
    public static function generateImageVariants(ProductImage $productImage)
    {
        // Placeholder untuk future implementation
        // Bisa menggunakan library seperti Intervention Image
        // untuk generate thumbnail dan resize variants

        return [
            'thumbnail' => $productImage->image_url,
            'medium' => $productImage->image_url,
            'large' => $productImage->image_url
        ];
    }
}
