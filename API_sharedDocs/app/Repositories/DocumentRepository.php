<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Document;


class DocumentRepository extends BaseRepository
{
    public function create(array $attributes)
    {
        return DB::transaction(function() use($attributes) {
            // $created = Document::query()->create([
            //     'title' => $request->title,
            //     'body' => $request->body
            // ]);

            // $created->users()->sync($request->user_ids);

            $created = Document::query()->create([
                'title' => data_get($attributes, 'title', 'Untitled'),
                'body' => data_get($attributes, 'body'),
            ]);

            if( $userIds = data_get($attributes, 'user_ids') ) {
                $created->users()->sync($userIds);
            }

            return $created;
        });
    }

    public function update($document, $attributes)
    {
        // $updatedSecond = $document->update([ $request->only(['title', 'body']) ]);

        return DB::transaction(function() use($document, $attributes) {
            $updated = $document->update([
                // we check if user updated the title, if not we keep the old one
                'title' => data_get($attributes, 'title', $document->title),
                'body' => data_get($attributes, 'body', $document->body)
            ]);

            if(!$updated) {
                throw new \Exception('Failed to update document');
            }

            if( $userIds = data_get($attributes, 'user_ids') ) {
                $document->users()->sync($userIds);
            }

            return $document;
        });
    }

    public function forceDelete($document)
    {
        return DB::transaction( function() use ($document) {
            $deleted = $document->forceDelete();

            if(!$deleted) {
                throw new \Exception('Cannot delete document.');
            }

            return $deleted;
        });
    }
}
