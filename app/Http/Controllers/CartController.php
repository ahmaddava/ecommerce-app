<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Terapkan middleware untuk memastikan hanya user yang sudah login
     * yang bisa mengakses semua method di controller ini.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan halaman utama keranjang belanja.
     * Method ini memuat halaman, bukan untuk AJAX.
     */
    public function index()
    {
        $cartData = $this->getCartData();
        return view('cart.index', $cartData);
    }

    /**
     * Menambahkan produk ke keranjang via AJAX.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'nullable|string'
        ]);

        $quantity = $request->input('quantity', 1);
        $size = $request->input('size');
        $product = Product::findOrFail($request->product_id);

        // Validasi stok berdasarkan ukuran atau produk
        if ($product->has_sizes && $size) {
            $productSize = $product->sizes()->where('size', $size)->first();
            if (!$productSize || $productSize->stock < $quantity) {
                return response()->json(['success' => false, 'message' => 'Stok produk ukuran ' . strtoupper($size) . ' tidak mencukupi.'], 422);
            }
        } else if (!$product->has_sizes) {
            if ($product->stock < $quantity) {
                return response()->json(['success' => false, 'message' => 'Stok produk tidak mencukupi.'], 422);
            }
        }

        // Cari item keranjang yang sama (produk dan ukuran)
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('size', $size)
            ->first();

        if ($cartItem) {
            // Jika produk sudah ada, update kuantitasnya
            $newQuantity = $cartItem->quantity + $quantity;

            // Validasi stok lagi untuk quantity baru
            if ($product->has_sizes && $size) {
                $productSize = $product->sizes()->where('size', $size)->first();
                if (!$productSize || $productSize->stock < $newQuantity) {
                    return response()->json(['success' => false, 'message' => 'Stok produk ukuran ' . strtoupper($size) . ' tidak mencukupi untuk jumlah ini.'], 422);
                }
            } else if (!$product->has_sizes) {
                if ($product->stock < $newQuantity) {
                    return response()->json(['success' => false, 'message' => 'Stok produk tidak mencukupi untuk jumlah ini.'], 422);
                }
            }

            $cartItem->increment('quantity', $quantity);
        } else {
            // Jika produk belum ada, buat entri baru
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
                'size' => $size,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang!',
            'cart' => $this->getCartData(),
        ]);
    }

    /**
     * Mengupdate kuantitas item di keranjang via AJAX.
     */
    public function update(Request $request, Cart $cart)
    {
        // Otorisasi: pastikan item keranjang milik user yang login
        if ($cart->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $request->validate(['quantity' => 'required|integer|min:1']);

        // Validasi stok
        if ($cart->product->stock < $request->quantity) {
            return response()->json(['success' => false, 'message' => 'Stok produk tidak mencukupi.'], 422);
        }

        $cart->update(['quantity' => $request->quantity]);

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil diupdate.',
            'cart' => $this->getCartData(),
        ]);
    }

    /**
     * Menghapus item dari keranjang via AJAX.
     */
    public function remove(Cart $cart)
    {
        // Otorisasi: pastikan item keranjang milik user yang login
        if ($cart->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus dari keranjang.',
            'cart' => $this->getCartData(),
        ]);
    }

    /**
     * Mengosongkan semua item dari keranjang via AJAX.
     */
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil dikosongkan.',
            'cart' => $this->getCartData(),
        ]);
    }

    /**
     * Mengambil jumlah total item untuk counter di navbar via AJAX.
     */
    public function count()
    {
        $count = Cart::where('user_id', Auth::id())->sum('quantity');
        return response()->json(['count' => $count]);
    }

    /**
     * Helper method untuk mengambil semua data keranjang.
     * Ini untuk menghindari duplikasi kode (prinsip DRY).
     */
    private function getCartData(): array
    {
        $userId = Auth::id();
        // Eager load relasi product dan category untuk optimasi
        $cartItems = Cart::with('product.category')->where('user_id', $userId)->get();

        $subtotal = $cartItems->sum(function ($item) {
            // Pastikan produk masih ada untuk menghindari error
            return $item->product ? $item->quantity * $item->product->price : 0;
        });

        return [
            'cartItems' => $cartItems,
            'count'     => (int) $cartItems->sum('quantity'),
            'subtotal'  => $subtotal,
        ];
    }
}
