<?php

namespace App\Services\Task;

use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Service
{
    /**
     * Get all tasks for a user
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getListTasksForUser($userId)
    {
        try {
            $tasks = Task::query()
                ->where('user_id', $userId)
                ->get();

            Log::info('User retrieved all tasks', [
                'user_id' => $userId,
                'task_count' => $tasks->count()
            ]);

            return $tasks;
        } catch (\Exception $e) {
            Log::error('Error retrieving tasks for user', [
                'user_id' => $userId,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);

            throw new \Exception('Ошибка при загрузке списка задач, попробуйте еще раз');
        }
    }

    /**
     * Store a new task
     *
     * @param array $data
     * @return Task
     */
    public function storeTask(array $data)
    {
        try {
            DB::beginTransaction();

            $task = Task::create($data);

            if (isset($data['tag_ids'])) {
                $task->tags()->attach($data['tag_ids']);
            }

            DB::commit();

            Log::info('Task created by user', [
                'user_id' => $data['user_id'],
                'task_id' => $task->id
            ]);

            return $task;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error creating task', [
                'user_id' => $data['user_id'],
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);

            throw new \Exception('Ошибка при добавлении задачи, повторите попытку');
        }
    }

    /**
     * Get a specific task by its ID
     *
     * @param int $id
     * @return Task
     */
    public function getTaskById($id)
    {
        try {
            $task = Task::query()->findOrFail($id);

            return $task;
        } catch (ModelNotFoundException $e) {
            Log::error('Error retrieving task', [
                'user_id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);

            throw new \Exception('Задача не найдена');
        } catch (\Exception $e) {
            Log::error('Error retrieving task', [
                'user_id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);

            throw new \Exception('Ошибка просмотра задачи, повторите попытку');
        }
    }

    /**
     * Update an existing task
     *
     * @param Task $task
     * @param array $data
     * @return Task
    */
    public function updateTask(Task $task, array $data)
    {
        try {
            DB::beginTransaction();

            $task->update($data);

            if (isset($data['tag_ids'])) {
                $task->tags()->sync($data['tag_ids']);
            }

            DB::commit();

            Log::info('Task updated by user', [
                'user_id' => $data['user_id'],
                'task_id' => $task->id
            ]);

            return $task;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error updating task', [
                'task_id' => $task->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);

            throw new \Exception('Ошибка при обновлении данных задачи, повторите попытку');
        }
    }

    /**
     * Delete a task
     *
     * @param Task $task
     * @return void
    */
    public function deleteTask(Task $task)
    {
        try {
            $task->delete();

            Log::info('Task deleted by user', [
                'user_id' => $task->user_id,
                'task_id' => $task->id
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting task', [
                'task_id' => $task->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);

            throw new \Exception('Ошибка при удалении задачи, повторите попытку');
        }
    }
}
