<?php

namespace App\Services;

use App\Models\Subcategory;

class SubcategoryService
{
    public function getAllSubcategories($payload = [])
    {
        $per_page = $payload['per_page'] ?? 10;
        $page = $payload['page'] ?? 1;
        $order_by = $payload['order_by'] ?? 'created_at';
        $order_direction = $payload['order_direction'] ?? 'asc';
        $search = $payload['search'] ?? null;
        $category_id = $payload['category_id'] ?? null;

        $query = Subcategory::query();

        if ($category_id) {
            $query->where('category_id', $category_id);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $query->with('category');
        $query->orderBy($order_by, $order_direction);

        return $query->paginate($per_page, ['*'], 'page', $page);
    }

    public function createSubcategory(array $data)
    {
        return Subcategory::create($data);
    }

    public function updateSubcategory(Subcategory $subcategory, array $data)
    {
        $subcategory->update($data);
        return $subcategory;
    }

    public function deleteSubcategory(Subcategory $subcategory)
    {
        $subcategory->delete();
    }

    public function bulkDeleteSubcategories(array $ids)
    {
        return Subcategory::whereIn('id', $ids)->delete();
    }
}
