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
        $tasks = Task::query()
            ->where('user_id', $userId)
            ->get();

        return $tasks;
    }

    /**
     * Store a new task
     *
     * @param array $data
     * @return Task
     */
    public function storeTask(array $data)
    {
        DB::beginTransaction();

        $task = Task::create($data);

        if (isset($data['tag_ids'])) {
            $task->tags()->attach($data['tag_ids']);
        }

        DB::commit();

        return $task;
    }

    /**
     * Get a specific task by its ID
     *
     * @param int $id
     * @return Task
     */
    public function getTaskById($id)
    {
        $task = Task::query()->findOrFail($id);

        return $task;
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
        DB::beginTransaction();

        $task->update($data);

        if (isset($data['tag_ids'])) {
            $task->tags()->sync($data['tag_ids']);
        }

        DB::commit();

        return $task;
    }

    /**
     * Delete a task
     *
     * @param Task $task
     * @return void
    */
    public function deleteTask(Task $task)
    {
        $task->delete();
    }
}
