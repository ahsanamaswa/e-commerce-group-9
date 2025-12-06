<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;

class HomeController extends Controller
{
    public function index()
    {
        // Jika user sudah login, tampilkan halaman user/home
        if (auth()->check()) {
            $products = Product::with(['store', 'category'])
                ->where('status', 'available')
                ->latest()
                ->paginate(12);
                
            $categories = ProductCategory::withCount('products')->get();
            
            return view('user.home', compact('products', 'categories'));
        }
        
        // Jika belum login, tampilkan landing page
        return view('pages.index');
    }

        // TAMBAHKAN METHOD INI
    public function dashboard()
    {
        $products = Product::with(['store', 'category'])
            ->latest()
            ->paginate(12);
            
        $categories = ProductCategory::withCount('products')->get();
        
        return view('pages.dashboard', compact('products', 'categories'));
    }
    public function search(Request $request)
    {
        $query = $request->input('q');
        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->paginate(12);
            
        return view('user.search', compact('products', 'query'));
    }

    public function categories()
    {
        $categories = ProductCategory::withCount('products')->get();
        return view('user.categories', compact('categories'));
    }

    public function category($slug)
    {
        $category = ProductCategory::where('slug', $slug)->firstOrFail();
        $products = Product::where('category_id', $category->id)
            ->where('status', 'available')
            ->paginate(12);
            
        return view('user.category', compact('category', 'products'));
    }
}