<?php

namespace App\Services\Admin\Tag;

use App\Models\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Service
{
    private const CACHE_KEY_TAGS = 'tags';

    /**
     * Deleted cache in redis
     *
     * @return void
    */
    public function resetCache(): void
    {
        Cache::forget(self::CACHE_KEY_TAGS);
    }

    /**
     * Get all tags for create task
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function indexTag(): \Illuminate\Database\Eloquent\Collection
    {
        $tags = Cache::remember(self::CACHE_KEY_TAGS, now()->addDay(), fn() => Tag::all());

        return $tags;
    }

    /**
     * Get a specific tag by its ID
     *
     * @param int $id
     * @return Tag
     */
    public function getTag(int $id): Tag
    {
        $tag = Tag::query()->findOrFail($id);

        return $tag;
    }

    /**
     * Store a new tag
     *
     * @param array $data
     * @return Tag
     */
    public function storeTag(array $data): Tag
    {
        $tag = Tag::create($data);

        $this->resetCache();

        return $tag;
    }

    /**
     * Update an tag
     *
     * @param int $id ,
     * @param array $data
     * @return Tag
     */
    public function updateTag(int $id, array $data): Tag
    {
        $tag = Tag::query()->findOrFail($id);

        $tag->update($data);

        $this->resetCache();

        return $tag;
    }

    /**
     * Delete a tag
     *
     * @param int $id,
     * @return void
     */
    public function deleteTag(int $id): void
    {
        $tag = Tag::query()->findOrFail($id);

        $tag->delete();

        $this->resetCache();

        Log::info('Deleted tag', [
            'tag_id' => $id,
            'tag_title' => $tag->title,
        ]);
    }
}
