<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'weight',
        'image',
        'category_id',
        'sku',
        'is_active',
        'has_sizes',
        'size_type',
        'has_multiple_images'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'weight' => 'decimal:2',
        'stock' => 'integer',
        'is_active' => 'boolean',
        'has_sizes' => 'boolean',
        'has_multiple_images' => 'boolean'
    ];

    /**
     * Relationship dengan Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship dengan ProductSize
     */
    public function sizes()
    {
        return $this->hasMany(ProductSize::class)->orderBy('size');
    }

    /**
     * Relationship dengan ProductImage
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class)->ordered();
    }

    /**
     * Relationship dengan order items
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relationship dengan cart items
     */
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get available sizes
     */
    public function availableSizes()
    {
        return $this->sizes()->available();
    }

    /**
     * Get primary image
     */
    public function primaryImage()
    {
        return $this->images()->primary()->first();
    }

    /**
     * Get product images (excluding size chart)
     */
    public function productImages()
    {
        return $this->images()->type('product');
    }

    /**
     * Get size chart image
     */
    public function sizeChartImage()
    {
        return $this->images()->type('size_chart')->first();
    }

    /**
     * Get detail images
     */
    public function detailImages()
    {
        return $this->images()->type('detail');
    }

    /**
     * Scope untuk produk aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk produk dengan ukuran
     */
    public function scopeWithSizes($query)
    {
        return $query->where('has_sizes', true);
    }

    /**
     * Scope untuk produk tanpa ukuran
     */
    public function scopeWithoutSizes($query)
    {
        return $query->where('has_sizes', false);
    }

    /**
     * Scope untuk produk yang tersedia (stock > 0)
     * Catatan: Ini berbeda dengan method isInStock() yang lebih kompleks.
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->has_multiple_images) {
            $primaryImage = $this->primaryImage();
            if ($primaryImage) {
                return $primaryImage->image_url;
            }
        }
        if ($this->image) {
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }
            return Storage::url($this->image);
        }
        return asset('images/no-image.png');
    }

    /**
     * Get total stock (untuk produk dengan ukuran)
    /**
     * Check if product is in stock
     */
    public function isInStock()
    {
        if ($this->has_sizes) {
            // For products with sizes, check if any size has stock
            return $this->sizes()->where('stock', '>', 0)->exists();
        } else {
            // For products without sizes, check main stock
            return $this->stock > 0;
        }
    }

    /**
     * Get total stock for products with sizes
     */
    public function getTotalStockAttribute()
    {
        if ($this->has_sizes) {
            return $this->sizes()->sum('stock');
        } else {
            return $this->stock;
        }
    }

    /**
     * Get available stock for a specific size
     */
    public function getStockForSize($size)
    {
        if (!$this->has_sizes) {
            return $this->stock;
        }

        $productSize = $this->sizes()->where('size', $size)->first();
        return $productSize ? $productSize->stock : 0;
    }

    /**
     * Reduce stock
     * Ini akan mengurangi stock produk utama jika tidak ada ukuran
     * atau Anda perlu memanggilnya pada ProductSize jika produk memiliki ukuran.
     * Pertimbangkan untuk menambahkan logika pengurangan stock per ukuran jika perlu.
     */
    public function reduceStock($quantity)
    {
        if (!$this->has_sizes && $this->stock >= $quantity) {
            $this->decrement('stock', $quantity);
            return true;
        }
        // Jika produk memiliki ukuran, pengurangan stock harus dilakukan pada ProductSize
        // Anda mungkin perlu metode terpisah atau parameter tambahan untuk ini.
        return false;
    }

    /**
     * Get stock status
     */
    public function getStockStatusAttribute()
    {
        if (!$this->is_active) {
            return 'inactive';
        }
        $totalStock = $this->total_stock;

        if ($totalStock <= 0) {
            return 'out_of_stock';
        }

        if ($totalStock <= 5) {
            return 'low_stock';
        }

        return 'in_stock';
    }

    /**
     * Get stock status label
     */
    public function getStockStatusLabelAttribute()
    {
        $labels = [
            'inactive' => 'Tidak Aktif',
            'out_of_stock' => 'Habis',
            'low_stock' => 'Stok Menipis',
            'in_stock' => 'Tersedia'
        ];
        return $labels[$this->stock_status] ?? 'Unknown';
    }

    /**
     * Get available sizes for display
     */
    public function getAvailableSizesListAttribute()
    {
        if (!$this->has_sizes) {
            return [];
        }
        // Pastikan ProductSize memiliki accessor formatted_size dan size_description
        return $this->availableSizes()->get()->map(function ($size) {
            return [
                'size' => $size->size,
                'label' => $size->formatted_size, // Asumsi ProductSize memiliki accessor ini
                'stock' => $size->stock,
                'description' => $size->size_description, // Asumsi ProductSize memiliki accessor ini
                'available' => $size->isInStock()
            ];
        })->toArray();
    }

    /**
     * Get size by size value
     */
    public function getSize($sizeValue)
    {
        return $this->sizes()->where('size', $sizeValue)->first();
    }

    /**
     * Check if specific size is available
     */
    public function isSizeAvailable($sizeValue)
    {
        if (!$this->has_sizes) {
            return false;
        }
        $size = $this->getSize($sizeValue);
        return $size && $size->isInStock();
    }

    /**
     * Get size type label
     */
    public function getSizeTypeLabelAttribute()
    {
        $labels = [
            'shirt' => 'Baju/Kaos',
            'pants' => 'Celana'
        ];
        return $labels[$this->size_type] ?? 'Pakaian';
    }

    /**
     * Auto-generate SKU if not provided
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->sku)) {
                $product->sku = 'PRD-' . strtoupper(uniqid());
            }
        });

        static::deleting(function ($product) {
            // Delete all related sizes
            $product->sizes()->delete();

            // Delete all related images
            $product->images()->each(function ($image) {
                $image->delete(); // This will trigger the image file deletion
            });
        });
    }
}
