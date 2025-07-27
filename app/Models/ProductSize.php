<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size',
        'stock',
        'length',
        'width',
        'is_available'
    ];

    protected $casts = [
        'stock' => 'integer',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'is_available' => 'boolean'
    ];

    /**
     * Relationship dengan Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope untuk ukuran yang tersedia
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)->where('stock', '>', 0);
    }

    /**
     * Scope untuk ukuran tertentu
     */
    public function scopeSize($query, $size)
    {
        return $query->where('size', $size);
    }

    /**
     * Get formatted size display
     */
    public function getFormattedSizeAttribute()
    {
        $sizeLabels = [
            'S' => 'Small',
            'M' => 'Medium',
            'L' => 'Large',
            'XL' => 'Extra Large',
            'XXL' => 'Double Extra Large'
        ];

        return $sizeLabels[$this->size] ?? $this->size;
    }

    /**
     * Get size description with measurements
     */
    public function getSizeDescriptionAttribute()
    {
        $description = $this->formatted_size;

        if ($this->length && $this->width) {
            $description .= " (P: {$this->length}cm, L: {$this->width}cm)";
        }

        return $description;
    }

    /**
     * Check if size is in stock
     */
    public function isInStock()
    {
        return $this->is_available && $this->stock > 0;
    }

    /**
     * Get stock status
     */
    public function getStockStatusAttribute()
    {
        if (!$this->is_available) {
            return 'unavailable';
        }

        if ($this->stock <= 0) {
            return 'out_of_stock';
        }

        if ($this->stock <= 5) {
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
            'unavailable' => 'Tidak Tersedia',
            'out_of_stock' => 'Habis',
            'low_stock' => 'Stok Menipis',
            'in_stock' => 'Tersedia'
        ];

        return $labels[$this->stock_status] ?? 'Unknown';
    }

    /**
     * Get stock status color for badge
     */
    public function getStockStatusColorAttribute()
    {
        $colors = [
            'unavailable' => 'secondary',
            'out_of_stock' => 'danger',
            'low_stock' => 'warning',
            'in_stock' => 'success'
        ];

        return $colors[$this->stock_status] ?? 'secondary';
    }
}
