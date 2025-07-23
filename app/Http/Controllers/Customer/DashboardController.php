<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders()->latest()->take(5)->get(); // Get latest 5 orders

        return view("customer.dashboard", compact("user", "orders"));
    }

    public function profile()
    {
        $user = Auth::user();
        return view("customer.profile", compact("user"));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|string|email|max:255|unique:users,email," . $user->id,
            "phone_number" => "nullable|string|max:20",
            "address" => "nullable|string|max:255",
            "city" => "nullable|string|max:255",
            "postal_code" => "nullable|string|max:10",
        ]);

        $user->update($request->all());

        return redirect()->back()->with("success", "Profil berhasil diperbarui!");
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = $user->orders()->latest()->paginate(10);
        return view("customer.orders", compact("orders"));
    }

    public function showOrder(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        return view("customer.order_detail", compact("order"));
    }
}


