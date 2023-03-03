<?php

namespace App\Repositories;

use App\Events\Comment\CommentCreated;
use App\Events\Comment\CommentDeleted;
use App\Events\Comment\CommentUpdated;
use Illuminate\Support\Facades\DB;
use App\Models\Comment;
use App\Exceptions\GeneralJsonException;


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

            throw_if(!$created, GeneralJsonException::class, 'Failed to create the comment.');

            // event( new CommentCreated($created));
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

            // if(!$updated) {
            //     throw new \Exception('Failed to update comment');
            // }
            throw_if(!$updated, GeneralJsonException::class, 'Failed to update the comment.');

            // event( new CommentUpdated($comment));
            return $comment;
        });
    }

    public function forceDelete($comment)
    {
        return DB::transaction( function() use($comment)
        {
            $deleted = $comment->forceDelete();

            // if(!$deleted) {
            //     throw new \Exception('Cannot delete comment.');
            // }
            throw_if(!$deleted, GeneralJsonException::class, 'Failed to delete the comment.');

            // event( new CommentDeleted($comment));
            return $deleted;
        });
    }
}
