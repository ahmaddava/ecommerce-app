<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'size'
    ];

    // Relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Get total price for this cart item
    public function getTotalAttribute()
    {
        return $this->quantity * $this->product->price;
    }

    // Get cart items for a specific user
    public static function getCartItems($userId)
    {
        return static::with('product')->where('user_id', $userId)->get();
    }

    // Get cart total for a specific user
    public static function getCartTotal($userId)
    {
        return static::with('product')->where('user_id', $userId)->get()->sum('total');
    }

    // Clear cart for a specific user
    public static function clearCart($userId)
    {
        return static::where('user_id', $userId)->delete();
    }
}
