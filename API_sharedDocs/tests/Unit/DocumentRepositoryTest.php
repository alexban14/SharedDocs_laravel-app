<?php

namespace Tests\Unit;


// use PHPUnit\Framework\TestCase;

use App\Exceptions\GeneralJsonException;
use App\Models\Document;
use App\Repositories\DocumentRepository;
use Tests\TestCase; // test class provided by laravel that gets you access to an app instance


class DocumentRepositoryTest extends TestCase
{
    // THESE ARE HAPPY TEST CASES

    // snake_case_name_convention for test names
    public function test_create()
    {
        // 1. Define the goal
        // test if create() actually creates a user

        // 2. Replicate the env / restriction
        $repository = $this->app->make(DocumentRepository::class);

        // 3. Define the source of truth
        $payload = [
            'title' => 'heyaa',
            'body' => []
        ];

        // 4. Compare the result
        $result = $repository->create($payload);

        // method that compares two values using ===
        $this->assertSame($payload['title'], $result->title, 'Document creation didn\'t work');
    }

    public function test_update()
    {
        // Goal: update the post using the update method

        // env
        $repository = $this->app->make(DocumentRepository::class);

        // grabbing the first element from the result of the create method
        $dummyDocument = Document::factory(1)->create()[0];

        // source of truth
        $payload = [
            'title' => 'abc123',
            'body' => []
        ];

        // compare result
        $updated = $repository->update($dummyDocument, $payload);
        // method that compares two values using ===
        $this->assertSame($payload['title'], $updated->title, 'Document update didn\'t work');
    }

    public function test_delete()
    {
        //  goal: test the delete method

        // env
        $repository = $this->app->make(DocumentRepository::class);
        $dummyDocument = Document::factory(1)->create()->first();

        // compare result
        $deleted = $repository->forceDelete($dummyDocument);
        // verify if deleted
        $found = Document::query()->find($dummyDocument->id);

        $this->assertSame(null, $found, 'Document wasn\'t delete');
    }

    // EDGE CASES TEST CASES - the ones that we actually need to cover
    public function test_delete_will_throw_exception_when_delete_document_that_doesnt_exist()
    {
        // env
        $repository = $this->app->make(DocumentRepository::class);
        $dummyDocument = Document::factory(1)->make()->first();

        $this->expectException(GeneralJsonException::class);
        $deleted = $repository->forceDelete($dummyDocument);
    }
}
