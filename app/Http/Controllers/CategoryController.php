<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\CategoryService;
use Inertia\Inertia;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\BulkDeleteCategoryRequest;

class CategoryController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        return Inertia::render('Categories');
    }

    public function apiIndex(Request $request): JsonResponse
    {
        $payload = $request->all();
        $payload['user_id'] = $request->user()->id;
        $categories = $this->categoryService->getAllCategories($payload);

        return response()->json([
            'data' => $categories->items(),
            'total' => $categories->total(),
            'per_page' => $categories->perPage(),
            'current_page' => $categories->currentPage(),
            'last_page' => $categories->lastPage(),
            'from' => $categories->firstItem(),
            'to' => $categories->lastItem(),
        ]);
    }


    public function apiShow(Category $category): JsonResponse
    {
        return response()->json($category);
    }

    public function apiGetCategoryPage(Request $request, Category $category): JsonResponse
    {
        if ($category->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $perPage = $request->input('per_page', 6);
        $search = $request->input('search', '');

        $page = $this->categoryService->getCategoryPage($category->id, $request->user()->id, $perPage, $search);

        return response()->json([
            'page' => $page,
            'category' => $category
        ]);
    }

    public function apiStore(StoreCategoryRequest $request): JsonResponse
    {
        $data = $request->all();
        $data['user_id'] = $request->user()->id;
        $category = $this->categoryService->createCategory($data);
        return response()->json($category, 201);
    }

    public function apiUpdate(Request $request, Category $category): JsonResponse
    {
        if ($category->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->all();
        $data['user_id'] = $request->user()->id;

        $category = $this->categoryService->updateCategory($category, $data);
        return response()->json($category);
    }

    public function apiDestroy(Request $request, Category $category): JsonResponse
    {
        if ($category->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $this->categoryService->deleteCategory($category);
        return response()->json(['message' => 'Category deleted successfully']);
    }

    public function apiBulkDestroy(BulkDeleteCategoryRequest $request): JsonResponse
    {
        $deletedCount = $this->categoryService->bulkDeleteCategories(
            $request->input('ids'),
            $request->user()->id
        );

        return response()->json([
            'message' => 'Categories deleted successfully',
            'deleted_count' => $deletedCount,
        ]);
    }
}
