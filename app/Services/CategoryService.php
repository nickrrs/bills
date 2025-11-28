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
        $with_subcategories = filter_var($payload['with_subcategories'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $subcategory_search = $payload['subcategory_search'] ?? null;

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

        if ($with_subcategories) {
            $query->whereHas('subcategories', function ($q) use ($subcategory_search) {
                if ($subcategory_search) {
                    $q->where('name', 'like', '%' . $subcategory_search . '%');
                }
            });

            $query->with(['subcategories' => function ($q) use ($subcategory_search) {
                if ($subcategory_search) {
                    $q->where('name', 'like', '%' . $subcategory_search . '%');
                }
                $q->orderBy('created_at', 'desc');
            }]);
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

    public function getCategoryPage(int $categoryId, int $userId, int $perPage = 6, string $search = '')
    {
        $query = Category::query()
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Conta quantas categorias existem antes da categoria desejada
        $categoriesBeforeCount = $query->clone()
            ->where('created_at', '>', function($subQuery) use ($categoryId) {
                $subQuery->select('created_at')
                    ->from('categories')
                    ->where('id', $categoryId);
            })
            ->count();

        // Calcula a página baseada na posição
        $page = intval(floor($categoriesBeforeCount / $perPage)) + 1;

        return $page;
    }
}
