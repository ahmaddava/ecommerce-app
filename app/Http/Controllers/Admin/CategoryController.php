<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $categories = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        try {
            // Log input data untuk debugging
            Log::info('Category Store Request', [
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->has('is_active'),
                'all_input' => $request->all()
            ]);

            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'description' => 'nullable|string|max:1000',
                'is_active' => 'boolean'
            ]);

            // Prepare data untuk create
            $categoryData = [
                'name' => trim($request->name),
                'description' => $request->description ? trim($request->description) : null,
                'is_active' => $request->has('is_active') ? true : false
            ];

            Log::info('Category Store Data', $categoryData);

            // Create kategori baru
            $category = Category::create($categoryData);

            Log::info('Category Store Result', [
                'created' => $category ? true : false,
                'category_id' => $category ? $category->id : null,
                'category_data' => $category ? $category->toArray() : null
            ]);

            if ($category) {
                return redirect()->route('admin.categories.index')
                    ->with('success', 'Kategori "' . $category->name . '" berhasil ditambahkan.');
            } else {
                return redirect()->back()
                    ->with('error', 'Gagal menambahkan kategori.')
                    ->withInput();
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Category Store Validation Error', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Category Store Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified category.
     */
    public function show($id)
    {
        $category = Category::with(['products' => function ($query) {
            $query->orderBy('created_at', 'desc')->take(10);
        }])->findOrFail($id);

        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        try {
            // Log input data untuk debugging
            Log::info('Category Update Request', [
                'category_id' => $id,
                'old_name' => $category->name,
                'new_name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->has('is_active'),
                'all_input' => $request->all()
            ]);

            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
                'description' => 'nullable|string',
                'is_active' => 'boolean'
            ]);

            // Prepare data untuk update
            $updateData = [
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->has('is_active') ? true : false
            ];

            Log::info('Category Update Data', $updateData);

            // Lakukan update
            $updated = $category->update($updateData);

            Log::info('Category Update Result', [
                'updated' => $updated,
                'category_after_update' => $category->fresh()->toArray()
            ]);

            if ($updated) {
                return redirect()->route('admin.categories.index')
                    ->with('success', 'Kategori berhasil diperbarui.');
            } else {
                return redirect()->back()
                    ->with('error', 'Gagal memperbarui kategori.')
                    ->withInput();
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Category Update Validation Error', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Category Update Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);

            // Log delete attempt
            Log::info('Category Delete Request', [
                'category_id' => $id,
                'category_name' => $category->name,
                'products_count' => $category->products()->count()
            ]);

            // Check if category has products
            $productsCount = $category->products()->count();
            if ($productsCount > 0) {
                Log::warning('Category Delete Blocked - Has Products', [
                    'category_id' => $id,
                    'category_name' => $category->name,
                    'products_count' => $productsCount
                ]);

                return redirect()->route('admin.categories.index')
                    ->with('error', 'Kategori "' . $category->name . '" tidak dapat dihapus karena masih memiliki ' . $productsCount . ' produk.');
            }

            // Store category name for success message
            $categoryName = $category->name;

            // Delete the category
            $deleted = $category->delete();

            if ($deleted) {
                Log::info('Category Delete Success', [
                    'category_id' => $id,
                    'category_name' => $categoryName
                ]);

                return redirect()->route('admin.categories.index')
                    ->with('success', 'Kategori "' . $categoryName . '" berhasil dihapus.');
            } else {
                Log::error('Category Delete Failed', [
                    'category_id' => $id,
                    'category_name' => $categoryName
                ]);

                return redirect()->route('admin.categories.index')
                    ->with('error', 'Gagal menghapus kategori "' . $categoryName . '".');
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Category Delete - Not Found', [
                'category_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Category Delete Exception', [
                'category_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.categories.index')
                ->with('error', 'Terjadi kesalahan saat menghapus kategori: ' . $e->getMessage());
        }
    }

    /**
     * Toggle category status.
     */
    public function toggleStatus($id)
    {
        $category = Category::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);

        $status = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori berhasil {$status}.");
    }
}
