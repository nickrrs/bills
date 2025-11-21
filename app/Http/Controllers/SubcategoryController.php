<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Http\JsonResponse;
use App\Services\SubcategoryService;
use App\Models\Subcategory;
use App\Http\Requests\BulkDeleteSubcategoryRequest;

class SubcategoryController extends Controller
{
    private $subcategoryService;

    public function __construct(SubcategoryService $subcategoryService)
    {
        $this->subcategoryService = $subcategoryService;
    }

    public function index()
    {
        return Inertia::render('Subcategories');
    }

    public function apiIndex(Request $request): JsonResponse
    {
        $payload = $request->all();
        $subcategories = $this->subcategoryService->getAllSubcategories($payload);

        return response()->json([
            'data' => $subcategories->items(),
            'total' => $subcategories->total(),
            'per_page' => $subcategories->perPage(),
            'current_page' => $subcategories->currentPage(),
            'last_page' => $subcategories->lastPage(),
            'from' => $subcategories->firstItem(),
            'to' => $subcategories->lastItem(),
        ]);
    }

    public function apiStore(Request $request): JsonResponse
    {
        $data = $request->all();
        $subcategory = $this->subcategoryService->createSubcategory($data);
        return response()->json($subcategory, 201);
    }

    public function apiUpdate(Request $request, Subcategory $subcategory): JsonResponse
    {
        $data = $request->all();
        $data['user_id'] = $request->user()->id;
        $subcategory = $this->subcategoryService->updateSubcategory($subcategory, $data);
        return response()->json($subcategory);
    }

    public function apiDestroy(Request $request, Subcategory $subcategory): JsonResponse
    {
        $this->subcategoryService->deleteSubcategory($subcategory);
        return response()->json(null, 204);
    }

    public function apiBulkDestroy(BulkDeleteSubcategoryRequest $request): JsonResponse
    {
        $deletedCount = $this->subcategoryService->bulkDeleteSubcategories(
            $request->input('ids')
        );

        return response()->json([
            'message' => 'Subcategories deleted successfully',
            'deleted_count' => $deletedCount,
        ]);
    }
}
