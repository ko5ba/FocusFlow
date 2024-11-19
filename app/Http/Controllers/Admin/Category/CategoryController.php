<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\Category\IndexResource;
use App\Http\Resources\Category\ShowResource;
use App\Services\Admin\Category\Service;

class CategoryController extends Controller
{
    protected $categoryService;
    public function __construct(Service $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = $this->categoryService->indexCategory();

            return IndexResource::collection($categories);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            $data = $request->validated();

            $category = $this->categoryService->storeCategory($data);

            return new ShowResource($category);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $categoryInfo = $this->categoryService->getCategory($id);

            return new ShowResource($categoryInfo);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            $data = $request->validated();

            $category = $this->categoryService->updateCategory($id, $data);

            return new ShowResource($category);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->categoryService->deleteCategory($id);

            return response()->json([
                'message' => 'Задача успешно удалена'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
