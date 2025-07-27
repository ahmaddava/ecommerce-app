<?php

use Illuminate\Http\Request;
use App\Services\SizeService;
use App\Services\ProductSizeService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Product Size API Routes
Route::get('/sizes/{categoryId}', function ($categoryId) {
    try {
        $category = \App\Models\Category::find($categoryId);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $requiresSizes = ProductSizeService::categoryRequiresSize($category->name);

        if (!$requiresSizes) {
            return response()->json([
                'success' => true,
                'requires_sizes' => false,
                'sizes' => []
            ]);
        }

        $sizeType = ProductSizeService::getSizeTypeByCategory($category->name);
        $sizes = ProductSizeService::getSizesByType($sizeType);

        return response()->json([
            'success' => true,
            'requires_sizes' => true,
            'size_type' => $sizeType,
            'sizes' => $sizes
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching sizes: ' . $e->getMessage()
        ], 500);
    }
});

// Get size details
Route::get('/size-details/{sizeType}/{size}', function ($sizeType, $size) {
    try {
        $sizeDetails = ProductSizeService::getSizeDetails($sizeType, $size);

        if (!$sizeDetails) {
            return response()->json([
                'success' => false,
                'message' => 'Size details not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'size_details' => $sizeDetails
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching size details: ' . $e->getMessage()
        ], 500);
    }
});

// Get all available sizes
Route::get('/all-sizes', function () {
    try {
        return response()->json([
            'success' => true,
            'sizes' => ProductSizeService::getAllSizes()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching sizes: ' . $e->getMessage()
        ], 500);
    }
});
