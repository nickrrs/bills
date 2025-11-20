<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function getAllCategories($payload = [])
    {
        $per_page = $payload['per_page'] ?? 10;
        $page = $payload['page'] ?? 1;
        $order_by = $payload['order_by'] ?? 'created_at';
        $order_direction = $payload['order_direction'] ?? 'desc';
        $search = $payload['search'] ?? null;
        $user_id = $payload['user_id'] ?? null;
        $type = $payload['type'] ?? null;

        $query = Category::query();

        if ($user_id) {
            $query->where('user_id', $user_id);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($type && in_array($type, ['expense', 'recept'], true)) {
            $query->where('type', $type);
        }

        $query->orderBy($order_by, $order_direction);

        return $query->paginate($per_page, ['*'], 'page', $page);
    }

    public function createCategory(array $data)
    {
        return Category::create($data);
    }

    public function updateCategory(Category $category, array $data)
    {
        $category->update($data);
        return $category;
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();
    }

    public function bulkDeleteCategories(array $ids, int $userId)
    {
        return Category::whereIn('id', $ids)
            ->where('user_id', $userId)
            ->delete();
    }
}
