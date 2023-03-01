<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Comment;


class CommentRepository extends BaseRepository
{
    public function create($attributes)
    {
        return DB::transaction( function() use($attributes)
        {
            $created = Comment::query()->create([
                'body' => data_get($attributes, 'body'),
                'user_id' => data_get($attributes, 'user_id', 1),
                'document_id' => data_get($attributes, 'document_id')
            ]);

            return $created;
        });

    }

    public function update($comment, $attributes)
    {
        return DB::transaction( function() use($comment, $attributes)
        {
            $updated = $comment->update([
                'body' => data_get($attributes, 'body', $comment->body),
                'user_id' => data_get($attributes, 'user_id', $comment->user_id),
                'document_id' => data_get($attributes, 'document_id', $comment->document_id)
            ]);

            if(!$updated) {
                throw new \Exception('Failed to update comment');
            }

            return $comment;
        });
    }

    public function forceDelete($comment)
    {
        return DB::transaction( function() use($comment)
        {
            $deleted = $comment->forceDelete();

            if(!$deleted) {
                throw new \Exception('Cannot delete comment.');
            }

            return $deleted;
        });
    }
}