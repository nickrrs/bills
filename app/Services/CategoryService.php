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
        $query = Category::where('user_id', $user_id);

        if ($user_id) {
            $query->where('user_id', $user_id);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
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
}
