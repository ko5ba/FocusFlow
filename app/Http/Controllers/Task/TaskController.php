<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\Task\IndexResource;
use App\Http\Resources\Task\ShowResource;
use App\Models\Task;
use App\Services\Task\Service;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(Service $taskService)
    {
        $this->taskService = $taskService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $tasks = $this->taskService->getListTasksForUser(Auth::id());

            return IndexResource::collection($tasks);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
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
            $task = $this->taskService->storeTask($data);

            return response()->json([
                'message' => 'Вы успешно создали задачу',
                'task' => new ShowResource($task),
                'tags' =>  $data['tag_ids'] ?? null
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $task = $this->taskService->getTaskById($id);
            Gate::authorize('view', $task);

            return new ShowResource($task);
        }

        catch (ModelNotFoundException $e) {

            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        }

        catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
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
            $task = $this->taskService->updateTask($task, $data);

            return response()->json([
                'message' => 'Вы успешно обновили задачу',
                'task' => new ShowResource($task),
                'tags' => $data['tags'] ?? null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try {
            Gate::authorize('delete', $task);

            $this->taskService->deleteTask($task);

            return response()->json([
                'message' => 'Вы успешно удалили задачу'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
