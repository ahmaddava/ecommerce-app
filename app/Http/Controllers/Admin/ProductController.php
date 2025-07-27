<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Services\SizeService; // Perhatikan: ini mungkin perlu diubah menjadi ProductSizeService
use App\Services\ProductSizeService; // Tambahkan ini
use App\Services\ProductImageService; // Tambahkan ini
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException; // Tambahkan ini untuk catch exception
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(10);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::active()->get();
        // Pastikan SizeService::getAllSizes() atau ProductSizeService::getAllSizes()
        // mengembalikan data yang Anda harapkan untuk form.
        // Jika Anda menggunakan ProductSizeService untuk logika ukuran,
        // maka mungkin lebih tepat memanggilnya di sini.
        $sizes = ProductSizeService::getAllSizes(); // Menggunakan ProductSizeService
        return view('admin.products.create', compact('categories', 'sizes'));
    }

    /**
     * Store a newly created product with enhanced functionality.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Product Store Request', [
                'request_data' => $request->except(['image', 'product_images', 'size_chart_image']),
                'has_image' => $request->hasFile('image'),
                'has_product_images' => $request->hasFile('product_images'),
                'has_size_chart' => $request->hasFile('size_chart_image'),
                'category_id' => $request->category_id
            ]);

            // Validation rules
            $rules = [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required|exists:categories,id',
                'sku' => 'nullable|string|unique:products,sku',
                'is_active' => 'boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'size_chart_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
            ];

            // Get category to determine if sizes are needed
            $category = Category::find($request->category_id);
            // Menggunakan ProductSizeService::categoryRequiresSize
            $requiresSizes = ProductSizeService::categoryRequiresSize($category->name);

            if ($requiresSizes) {
                $rules['has_sizes'] = 'boolean';
                $rules['size_type'] = 'required|in:shirt,pants';
                $rules['sizes'] = 'required|array|min:1';
                $rules['sizes.*.size'] = 'required|string';
                $rules['sizes.*.stock'] = 'required|integer|min:0';
                // Tambahkan validasi untuk weight jika has_sizes true, tapi stock utama 0
                $rules['weight'] = 'nullable|numeric|min:0'; // Weight masih relevan meskipun stock di ProductSize
            } else {
                $rules['stock'] = 'required|integer|min:0';
                $rules['weight'] = 'required|numeric|min:0';
            }

            $validatedData = $request->validate($rules);

            Log::info('Product Validation Passed', ['validated_data' => $validatedData]);

            // Create product
            $productData = [
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'price' => $validatedData['price'],
                'category_id' => $validatedData['category_id'],
                'sku' => $validatedData['sku'] ?? $this->generateSKU($validatedData['name']), // Gunakan generateSKU
                'is_active' => $request->boolean('is_active', true),
                'has_sizes' => $requiresSizes,
                'size_type' => $requiresSizes ? ProductSizeService::getSizeTypeByCategory($category->name) : null,
                'stock' => $requiresSizes ? 0 : $validatedData['stock'], // Stock produk utama 0 jika has_sizes true
                'weight' => $validatedData['weight'] ?? ($requiresSizes ? 0 : null), // Weight bisa null jika tidak ada ukuran
                'has_multiple_images' => $request->hasFile('product_images') || $request->hasFile('size_chart_image')
            ];

            // Handle single image upload (legacy)
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('products', $imageName, 'public');
                $productData['image'] = $imagePath;
            }

            $product = Product::create($productData);

            Log::info('Product Created', ['product_id' => $product->id, 'product_data' => $productData]);

            // Handle sizes if required
            if ($requiresSizes && isset($validatedData['sizes'])) {
                ProductSizeService::createProductSizes($product, $validatedData['sizes']);
                Log::info('Product Sizes Created', ['product_id' => $product->id, 'sizes_count' => count($validatedData['sizes'])]);
            }

            // Handle multiple images
            $imageResults = [];

            // Product images
            if ($request->hasFile('product_images')) {
                $productImages = ProductImageService::uploadMultipleImages(
                    $request->file('product_images'),
                    $product,
                    'product'
                );
                $imageResults['product'] = $productImages;
                Log::info('Product Images Uploaded', ['product_id' => $product->id, 'images_count' => count($productImages)]);
            }

            // Size chart image
            if ($request->hasFile('size_chart_image')) {
                $sizeChartImage = ProductImageService::uploadProductImage(
                    $request->file('size_chart_image'),
                    $product,
                    'size_chart'
                );
                $imageResults['size_chart'] = [$sizeChartImage];
                Log::info('Size Chart Image Uploaded', ['product_id' => $product->id, 'image_id' => $sizeChartImage->id]);
            }

            // Update has_multiple_images flag
            $hasMultipleImages = !empty($imageResults['product']) || !empty($imageResults['size_chart']);
            if ($hasMultipleImages) {
                $product->update(['has_multiple_images' => true]);
            }

            Log::info('Product Store Success', [
                'product_id' => $product->id,
                'has_sizes' => $product->has_sizes,
                'has_multiple_images' => $product->has_multiple_images
            ]);

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk "' . $product->name . '" berhasil ditambahkan.');
        } catch (ValidationException $e) { // Menggunakan ValidationException
            Log::warning('Product Store Validation Failed', [
                'errors' => $e->errors(),
                'request_data' => $request->except(['image', 'product_images', 'size_chart_image'])
            ]);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam data yang diinput.');
        } catch (\Exception $e) {
            Log::error('Product Store Exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['image', 'product_images', 'size_chart_image'])
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan produk: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $product = Product::with(['category', 'images', 'sizes'])->findOrFail($id); // Eager load images and sizes
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit($id)
    {
        $product = Product::with(['images', 'sizes'])->findOrFail($id); // Eager load images and sizes for edit
        $categories = Category::active()->get();
        $sizes = ProductSizeService::getAllSizes(); // Menggunakan ProductSizeService
        return view('admin.products.edit', compact('product', 'categories', 'sizes'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id, // Unique except current product
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'size_chart_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ];

        // Get category to determine if sizes are needed
        $category = Category::find($request->category_id);
        $requiresSizes = ProductSizeService::categoryRequiresSize($category->name);

        if ($requiresSizes) {
            $rules['has_sizes'] = 'boolean';
            $rules['size_type'] = 'required|in:shirt,pants';
            $rules['sizes'] = 'required|array|min:1';
            $rules['sizes.*.size'] = 'required|string';
            $rules['sizes.*.stock'] = 'required|integer|min:0';
            $rules['weight'] = 'nullable|numeric|min:0';
        } else {
            $rules['stock'] = 'required|integer|min:0';
            $rules['weight'] = 'required|numeric|min:0';
        }

        $validatedData = $request->validate($rules);

        $productData = [
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'category_id' => $validatedData['category_id'],
            'sku' => $validatedData['sku'] ?? $this->generateSKU($validatedData['name']),
            'is_active' => $request->boolean('is_active', true),
            'has_sizes' => $requiresSizes,
            'size_type' => $requiresSizes ? ProductSizeService::getSizeTypeByCategory($category->name) : null,
            'stock' => $requiresSizes ? 0 : $validatedData['stock'],
            'weight' => $validatedData['weight'] ?? ($requiresSizes ? 0 : null),
            'has_multiple_images' => $request->hasFile('product_images') || $request->hasFile('size_chart_image') || $product->images()->exists()
        ];

        // Handle single image upload (legacy)
        if ($request->hasFile('image')) {
            // Delete old image if it's a single image (not part of multiple images)
            if ($product->image && !$product->has_multiple_images) {
                Storage::disk('public')->delete($product->image);
            }
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('products', $imageName, 'public');
            $productData['image'] = $imagePath;
        } elseif (!$request->hasFile('image') && !$product->has_multiple_images) {
            // If no new single image is uploaded and it's not a multiple image product,
            // and the old image was present, keep it.
            // If you want to allow removing the single image, add a checkbox for it.
        }


        $product->update($productData);

        // Handle sizes if required
        if ($requiresSizes) {
            // Hapus semua ukuran lama dan buat yang baru
            $product->sizes()->delete();
            if (isset($validatedData['sizes'])) {
                ProductSizeService::createProductSizes($product, $validatedData['sizes']);
            }
        } else {
            // Jika produk tidak lagi memerlukan ukuran, hapus semua ukuran terkait
            $product->sizes()->delete();
            // Pastikan stock dan weight diupdate langsung di produk
            $product->update([
                'stock' => $validatedData['stock'],
                'weight' => $validatedData['weight'],
                'has_sizes' => false,
                'size_type' => null,
            ]);
        }

        // Handle multiple images
        // Untuk update, Anda mungkin perlu logika yang lebih kompleks
        // seperti menghapus gambar lama yang tidak lagi ada, atau mengupdate metadata.
        // Untuk saat ini, kita akan menambahkan gambar baru.
        if ($request->hasFile('product_images')) {
            ProductImageService::uploadMultipleImages(
                $request->file('product_images'),
                $product,
                'product'
            );
        }

        if ($request->hasFile('size_chart_image')) {
            // Hapus size chart lama jika ada
            $oldSizeChart = $product->sizeChartImage();
            if ($oldSizeChart) {
                $oldSizeChart->delete(); // Ini akan memicu deleteImageFile()
            }
            ProductImageService::uploadProductImage(
                $request->file('size_chart_image'),
                $product,
                'size_chart'
            );
        }

        // Update has_multiple_images flag based on current state
        $product->has_multiple_images = $product->images()->exists();
        $product->save();


        return redirect()->route('admin.products.index')
            ->with('success', 'Produk "' . $product->name . '" berhasil diperbarui.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Logika penghapusan gambar sudah ada di Product model boot method
        // (melalui ProductImage::deleting) dan di Product model itu sendiri
        // untuk 'image' tunggal.
        // Anda mungkin ingin menghapus 'image' tunggal secara manual di sini
        // jika tidak menggunakan ProductImage untuk itu.
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete(); // Ini akan memicu deleting event di Product model

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus');
    }

    /**
     * Generate unique SKU.
     */
    private function generateSKU($name)
    {
        $prefix = strtoupper(substr(Str::slug($name), 0, 3));
        // Pastikan SKU unik, tambahkan angka jika sudah ada
        $baseSku = $prefix . '-';
        $i = 1;
        do {
            $sku = $baseSku . str_pad($i, 3, '0', STR_PAD_LEFT);
            $i++;
        } while (Product::where('sku', $sku)->exists());

        return $sku;
    }
}
