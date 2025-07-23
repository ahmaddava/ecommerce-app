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
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
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
        $category = Category::findOrFail($id);

        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
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
