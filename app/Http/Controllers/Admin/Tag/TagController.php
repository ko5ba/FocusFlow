<?php

namespace App\Http\Controllers\Admin\Tag;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tag\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\Tag\IndexResource;
use App\Http\Resources\Tag\ShowResource;
use App\Models\Tag;
use App\Services\Admin\Tag\Service;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TagController extends Controller
{
    protected $tagService;

    public function __construct(Service $tagService)
    {
        $this->tagService = $tagService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $tags = $this->tagService->indexTag();

            return IndexResource::collection($tags);
        } catch (\Exception) {
            return response()->json([
                'error' => 'Не удалось загрузить список тегов, повторите попытку'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            $data = $request->validated();

            $tag = $this->tagService->storeTag($data);

            return new ShowResource($tag);
        } catch (\Exception) {
            return response()->json([
                'message' => 'Не удалось создать тег, повторите попытку'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $tag = $this->tagService->getTag($id);

            return new ShowResource($tag);
        }

        catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Тег не найден'
            ], 404);
        }

        catch (\Exception) {
            return response()->json([
                'message' => 'Не удалось загрузить данные о теге, повторите попытку'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            $data = $request->validated();

            $tag = $this->tagService->updateTag($id, $data);

            return new ShowResource($tag);
        }

        catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Категория не найден'
            ], 404);
        }

        catch (\Exception) {
            return response()->json(
                ['message' => 'Не удалось обновить информацию о теге, повторите попытку'],
                500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->tagService->deleteTag($id);

            return response()->json([
                'message' => 'Вы успешно удалили тег'
            ]);
        }

        catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Тег не найден'
            ], 404);
        }

        catch (\Exception) {
            return response()->json([
                'message' => 'Не удалось удалить тег, повторите попытку'
            ], 500);
        }
    }
}
