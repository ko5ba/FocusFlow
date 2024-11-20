<?php

namespace App\Services\Admin\Category;

use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Service
{
    private const CACHE_KEY_CATEGORIES = 'categories';

    /**
     * Deleted cache in redis
     *
     * @return void
    */
    private function resetCache(): void
    {
        Cache::forget(self::CACHE_KEY_CATEGORIES);
    }

     /**
     * Get all categories for create task
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function indexCategory(): \Illuminate\Database\Eloquent\Collection
    {
        $categories = Cache::remember(self::CACHE_KEY_CATEGORIES, 60 * 60 * 24, fn() => Category::all());

        return $categories;
    }


    /**
     * Get a specific category by its ID
     *
     * @param int $id
     * @return Category
     */
    public function getCategory(int $id): Category
    {
        $category = Category::query()->findOrFail($id);

        return $category;
    }
    /**
     * Store a new category
     *
     * @param array $data
     * @return Category
     */
    public function storeCategory(array $data): Category
    {
        $category = Category::create($data);

        $this->resetCache();

        return $category;
    }
    /**
     * Update an category
     *
     * @param int $id ,
     * @param array $data
     * @return Category
     */
    public function updateCategory(int $id, array $data): Category
    {
        $category = Category::query()->findOrFail($id);

        $category->update($data);

        $this->resetCache();

        return $category;
    }

    /**
     * Delete a category
     *
     * @param int $id,
     * @return void
    */
    public function deleteCategory(int $id): void
    {
        $category = Category::query()->findOrFail($id);

        $category->delete();

        $this->resetCache();
    }
}
