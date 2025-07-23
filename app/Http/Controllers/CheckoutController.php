<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cartItems = Cart::where("user_id", $user->id)->with("product")->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route("cart.index")->with("error", "Keranjang belanja Anda kosong.");
        }

        $subtotal = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        // For simplicity, assume shipping is fixed for now
        $shippingCost = 25000; 
        $totalAmount = $subtotal + $shippingCost;

        return view("checkout.index", compact("cartItems", "subtotal", "shippingCost", "totalAmount"));
    }

    public function process(Request $request)
    {
        $request->validate([
            "address" => "required|string|max:255",
            "city" => "required|string|max:255",
            "postal_code" => "required|string|max:10",
            "phone_number" => "required|string|max:20",
            "payment_method" => "required|string|in:bni,qris,bca",
        ]);

        $user = Auth::user();
        $cartItems = Cart::where("user_id", $user->id)->with("product")->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route("cart.index")->with("error", "Keranjang belanja Anda kosong.");
        }

        DB::beginTransaction();
        try {
            $subtotal = $cartItems->sum(function($item) {
                return $item->product->price * $item->quantity;
            });

            $shippingCost = 25000; 
            $totalAmount = $subtotal + $shippingCost;

            $order = Order::create([
                "user_id" => $user->id,
                "order_number" => "ORD-" . time() . rand(100, 999),
                "customer_name" => $user->name,
                "customer_email" => $user->email,
                "total_amount" => $totalAmount,
                "shipping_cost" => $shippingCost,
                "status" => "pending",
                "payment_status" => "pending",
                "payment_method" => $request->payment_method,
                "shipping_address" => $request->address . ", " . $request->city . ", " . $request->postal_code,
                "customer_phone" => $request->phone_number,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    "order_id" => $order->id,
                    "product_id" => $item->product_id,
                    "quantity" => $item->quantity,
                    "price" => $item->product->price,
                ]);

                // Update product stock
                $product = $item->product;
                $product->stock -= $item->quantity;
                $product->save();
            }

            // Clear cart
            Cart::where("user_id", $user->id)->delete();

            // Create Midtrans transaction
            $midtransService = new MidtransService();
            $snapToken = $midtransService->createTransaction($order);
            
            // Store snap token in order
            $order->payment_reference = $snapToken;
            $order->save();

            DB::commit();

            return redirect()->route("checkout.success", $order->id)->with("success", "Pesanan Anda berhasil dibuat!");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with("error", "Terjadi kesalahan saat memproses pesanan: " . $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        return view("checkout.success", compact("order"));
    }

    // Midtrans notification handler
    public function notification(Request $request)
    {
        try {
            $midtransService = new MidtransService();
            $order = $midtransService->handleNotification($request);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Notification handled successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Payment gateway callback (dummy for now)
    public function callback(Request $request)
    {
        // In a real application, this would handle callbacks from payment gateways
        // and update order payment status accordingly.
        // For now, we'll just log the request.
        
        // Example: Update order status to paid if payment is successful
        // $order = Order::find($request->order_id);
        // if ($order && $request->status == 'paid') {
        //     $order->payment_status = 'paid';
        //     $order->save();
        // }

        return response()->json(["message" => "Callback received"], 200);
    }
}


