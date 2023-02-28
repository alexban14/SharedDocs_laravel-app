<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\DocumentResource;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = Document::get()->all();

        // return new JsonResponse([
        //     'documents' => $documents
        // ]);

        // created a generalized response as a helper class for Document model
        return DocumentResource::collection($documents);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $created = DB::transaction(function() use ($request) {
            $created = Document::query()->create([
                'title' => $request->title,
                'body' => $request->body
            ]);

            $created->users()->sync($request->user_ids);

            return $created;
        });

        return new JsonResponse([
            'document' => $created
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        // return new JsonResponse([
        //     'document' => $document
        // ]);

        // created a generalized response as a helper class for Document model
        return new DocumentResource($document);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        // $updatedSecond = $document->update([ $request->only(['title', 'body']) ]);

        $updated = $document->update([
            // we check if user updated the title, if not we keep the old one
            'title' => $request->title?? $document->title,
            'body' => $request->body?? $document->body,
        ]);

        if($updated) {
            return new JsonResponse([
                'updated_document' => $updated
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        $deleted = $document->forceDelete();

        if(!$deleted) {
            return new JsonResponse([
                'errors' => [
                    'Could not delete resource.'
                ]
            ], 400);
        }

        return new JsonResponse([
            'deleted' => $deleted
        ], 400);
    }
}
