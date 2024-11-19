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
        try {
            $categories = Cache::remember(self::CACHE_KEY_CATEGORIES, 60 * 60 * 24, fn() => Category::all());

            Log::info('Categories have been cached', [
                'categories_count' => $categories->count()
            ]);

            return $categories;
        } catch (\Exception $e) {
            Log::error('Categories were not cached', [
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);

            throw new \Exception('Ошибка при получении списка категорий');
        }
    }

    /**
     * Get a specific category by its ID
     *
     * @param int $id
     * @return Category
     */
    public function getCategory(int $id): Category
    {
        try {
            $category = Category::query()->findOrFail($id);

            Log::info('Preview specific category', [
                'category_id' => $category->id,
                'category_title' => $category->title,
            ]);

            return $category;
        } catch (ModelNotFoundException $e) {
            Log::error('Category not found', [
                'category_id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);

            throw new \Exception('Категория не найдена');
        } catch (\Exception $e) {
            Log::error('Unknown error', [
                'category_id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);

            throw new \Exception('Неизвестная ошибка');
        }
    }

    /**
     * Store a new category
     *
     * @param array $data
     * @return Category
     */
    public function storeCategory(array $data): Category
    {
        try {
            $category = Category::create($data);

            $this->resetCache();

            Log::info('Created category', [
                'category_id' => $category->id,
                'category_title' => $category->title,
            ]);

            return $category;
        } catch (\Exception $e) {
            Log::error('Category were not created', [
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);

            throw new \Exception('Не удалось добавить новую категорию, повторите попытку');
        }
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
        try {
            $category = Category::query()->findOrFail($id);

            $category->update($data);

            $this->resetCache();

            Log::info('Updated category', [
                'category_id' => $category->id,
                'category_title' => $category->title,
            ]);

            return $category;
        } catch (ModelNotFoundException $e) {
            Log::error('Category not found', [
                'category_id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);

            throw new \Exception('Category not found');
        } catch (\Exception $e) {
            Log::error('Unknown error', [
                'category_id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);

            throw new \Exception('Не удалось обновить задачу, повторите попытку');
        }
    }
    /**
     * Delete a category
     *
     * @param int $id,
     * @return void
    */
    public function deleteCategory(int $id): void
    {
        try {
            $category = Category::query()->findOrFail($id);

            $category->delete();

            $this->resetCache();

            Log::info('Deleted category', [
                'category_id' => $id,
                'category_title' => $category->title,
            ]);
        } catch (ModelNotFoundException $e) {
            Log::error('Category not found', [
                'category_id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);

            throw new \Exception('Категория не найдена');
        } catch (\Exception $e) {
            Log::error('Unknown error', [
                'category_id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);

            throw new \Exception('Ошибка при удалении категории, повторите попытку');
        }
    }
}
