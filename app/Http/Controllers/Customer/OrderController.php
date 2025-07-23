<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        // Pastikan hanya user yang sudah login yang bisa mengakses
        $this->middleware('auth');
    }

    /**
     * Menampilkan daftar semua pesanan milik customer.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest() // Urutkan dari yang terbaru
            ->paginate(10); // Tampilkan 10 per halaman

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail satu pesanan.
     */
    public function show(Order $order)
    {
        // Keamanan: Pastikan customer hanya bisa melihat pesanannya sendiri
        if ($order->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }

        return view('customer.orders.show', compact('order'));
    }
}
