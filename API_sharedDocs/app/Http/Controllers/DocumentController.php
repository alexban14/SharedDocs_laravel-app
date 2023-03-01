<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\DocumentResource;
use App\Repositories\DocumentRepository;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageSize = $request->page_size ?? 20;
        $documents = Document::query()->paginate($pageSize);

        // return new JsonResponse([
        //     'documents' => $documents
        // ]);

        // created a generalized response as a helper class for Document model
        return DocumentResource::collection($documents);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, DocumentRepository $repository)
    {
        $created = $repository->create($request->only([
            'title',
            'body',
            'user_ids'
        ]));

        return new DocumentResource($created);
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
    public function update(Request $request, Document $document, DocumentRepository $repository)
    {
        $updated = $repository->update($document, $request->only([
            'title',
            'body',
            'user_ids'
        ]));

        return new DocumentResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document, DocumentRepository $repository)
    {
        $document = $repository->forceDelete($document);
        return new JsonResponse([
            'data' => 'success'
        ]);
    }
}
