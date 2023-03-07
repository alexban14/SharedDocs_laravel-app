<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralJsonException;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\DocumentResource;
use App\Models\User;
use App\Notifications\DocumentSharedNotification;
use App\Repositories\DocumentRepository;
use App\Rules\IntegerArray;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // exception class helper function
        // report(GeneralJsonException::class);
        // abort(404);

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
    public function store(StoreDocumentRequest $request, DocumentRepository $repository)
    {
        // payload = the request body
        $payload = $request->only([
            'title',
            'body',
            'user_ids'
        ]);

        // Validation using validator facade
        // $validator = Validator::make($payload /** first argument, the actual data that needs validation */, [
        //     // first argument, validation rules
        //     'title' => ['string', 'required'],
        //     'body' => ['string', 'required'],
        //     'user_ids' => [
        //         'array',
        //         'required',
        //         // custom validation with
        //         new IntegerArray()
        //     ]
        // ], [
        //     // seconde argument, custom validation messages
        //     'body.required' => 'Please enter a value for body',
        //     'title.string' => 'Please use strings a the title body',
        // ], [
        //     // third argument, custom attributes name
        //     'user_ids' => 'User Ids'
        // ]);

        // $validator->validate();

        $created = $repository->create($payload);

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
    public function update(UpdateDocumentRequest $request, Document $document, DocumentRepository $repository)
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

        /**
     * Share a specified post from storage.
     * @response 200 {
     *  "data": "signed url..."
     * }
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\JsonResponse
     */
    public function share(Request $request, Document $document)
    {
        $url = URL::temporarySignedRoute('shared.document', now()->addDays(30), [
            'document' => $document->id,
        ]);

        $users = User::query()->whereIn('id', $request->user_ids)->get();

        // notification facade to send notification
        Notification::send($users, new DocumentSharedNotification($document, $url));

        // an alternative to use the notify method on a user instance
        // $user = User::query()->find(1);
        // $user->notify( new DocumentSharedNotification($document, $url));

        return new JsonResponse([
            'data' => $url,
        ]);
    }
}
