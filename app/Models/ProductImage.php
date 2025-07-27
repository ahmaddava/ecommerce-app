<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'image_type',
        'alt_text',
        'sort_order',
        'is_primary'
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_primary' => 'boolean'
    ];

    /**
     * Relationship dengan Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope untuk gambar utama
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope untuk tipe gambar tertentu
     */
    public function scopeType($query, $type)
    {
        return $query->where('image_type', $type);
    }

    /**
     * Scope untuk urutan tampil
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Get full URL gambar
     */
    public function getImageUrlAttribute()
    {
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }

        return Storage::url($this->image_path);
    }

    /**
     * Get image type label
     */
    public function getImageTypeLabelAttribute()
    {
        $labels = [
            'product' => 'Gambar Produk',
            'size_chart' => 'Tabel Ukuran',
            'detail' => 'Detail Produk'
        ];

        return $labels[$this->image_type] ?? 'Gambar';
    }

    /**
     * Check if image file exists
     */
    public function imageExists()
    {
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return true; // Assume external URLs exist
        }

        return Storage::exists($this->image_path);
    }

    /**
     * Delete image file from storage
     */
    public function deleteImageFile()
    {
        if (!filter_var($this->image_path, FILTER_VALIDATE_URL) && Storage::exists($this->image_path)) {
            return Storage::delete($this->image_path);
        }

        return true;
    }

    /**
     * Boot method untuk auto-delete file saat model dihapus
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($productImage) {
            $productImage->deleteImageFile();
        });
    }

    /**
     * Get thumbnail URL (untuk admin interface)
     */
    public function getThumbnailUrlAttribute()
    {
        // Untuk saat ini return image URL yang sama
        // Bisa dikembangkan untuk generate thumbnail
        return $this->image_url;
    }

    /**
     * Set as primary image (dan unset yang lain)
     */
    public function setAsPrimary()
    {
        // Unset semua primary images untuk produk ini
        self::where('product_id', $this->product_id)
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);

        // Set image ini sebagai primary
        $this->update(['is_primary' => true]);
    }

    /**
     * Get next sort order untuk produk
     */
    public static function getNextSortOrder($productId)
    {
        $maxOrder = self::where('product_id', $productId)->max('sort_order');
        return ($maxOrder ?? 0) + 1;
    }
}
