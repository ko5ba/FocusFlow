<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::factory(100)->create()->each(function ($task) {
            $tags = Tag::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $task->tags()->attach($tags);
        });
    }
}
