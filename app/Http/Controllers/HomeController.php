<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Show the application home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $featuredProducts = Product::with('category')
            ->active()
            ->inStock()
            ->take(8)
            ->get();
            
        $categories = Category::active()->take(6)->get();
        
        return view('home', compact('featuredProducts', 'categories'));
    }

    /**
     * Show the application dashboard for authenticated users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('customer.dashboard');
    }
}
