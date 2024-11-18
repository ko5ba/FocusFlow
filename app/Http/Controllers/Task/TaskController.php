<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\Task\IndexResource;
use App\Http\Resources\Task\ShowResource;
use App\Models\Task;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $tasks = Task::query()
                ->where('user_id', Auth::id())
                ->get();

            Log::info('Пользователь, просматривает список своих задач', [
                'user_id' => Auth::id(),
                'task_count' => $tasks->count()
            ]);

            return IndexResource::collection($tasks);
        } catch (\Exception $e) {
            Log::error('Ошибка при просмотре всех задач пользователем: ', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);

            return response()->json([
                'message' => 'Ошибка при загрузке списка задач, попробуйте еще раз'
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
            $data['user_id'] = Auth::id();
            $tags = $data['tag_ids'] ?? [];
            unset($data['tag_ids']);

            DB::beginTransaction();
            $task = Task::create($data);
            $task->tags()->attach($tags);
            DB::commit();

            Log::info('Создание пользователем задачи', [
                'user_id' => Auth::id(),
                'task_id' => $task->id,
                'tags' => $tags
            ]);

            return response()->json([
                'message' => 'Вы успешно создали задачу',
                'task' => new ShowResource($task),
                'tags' => $tags
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Ошибка при добавлении задачи', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);

            return response()->json([
                'message' => 'Ошибка при добавлении задачи, повторите попытку'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        try {
            Gate::authorize('view', $task);

            Log::info('Просматривание задачи', [
                'user_id' => Auth::id(),
                'task_id' => $task->id
            ]);

            return new ShowResource($task);
        } catch (\Exception $e) {
            Log::error('Ошибка при просмотре задачи', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);

            return response()->json([
                'message' => 'Ошибка при просмотре задачи, повторите попытку'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Task $task)
    {
        try {
            Gate::authorize('update', $task);

            $data = $request->validated();
            $data['user_id'] = Auth::id();
            $tags = $data['tag_ids'] ?? [];
            unset($data['tag_ids']);

            DB::beginTransaction();
            $task->update($data);
            $task->tags()->sync($tags);
            DB::commit();

            Log::info('Обновление данных задачи', [
                'user_id' => Auth::id(),
                'task_id' => $task->id,
                'tags' => $tags
            ]);

            return response()->json([
                'message' => 'Вы успешно обновили задачу',
                'task' => new ShowResource($task),
                'tags' => $tags
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Ошибка при обновлении задачи', [
                'user_id' => Auth::id(),
                'task_id' => $task->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);

            return response()->json([
                'message' => 'Ошибка при обновлении данных задачи, повторите попытку'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Task $task)
    {
        try {
            Gate::authorize('delete', $task);

            $task->delete();

            Log::info('Удаление задачи', [
                'user_id' => Auth::id(),
                'task_id' => $task->id
            ]);

            return response()->json([
                'message' => 'Вы успешно удалили задачу, но можете ее восстановить в течении двух часов'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Ошибка удаления задачи', [
                'user_id' => Auth::id(),
                'task_id' => $task->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);

            return response()->json([
                'message' => 'Ошибка при удалении задачи, повторите попытку'
            ], 500);
        }
    }
}
