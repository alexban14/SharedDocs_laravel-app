<?php

namespace Tests\Feature;

use App\Events\Models\Document\DocumentCreated;
use App\Events\Models\Document\DocumentDeleted;
use App\Events\Models\Document\DocumentUpdated;
use App\Models\Document;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class DocumentApiTest extends TestCase
{
    use RefreshDatabase; // a trait that resets all the data from the db

    public function test_index()
    {
        // load data in the database
        $documents = Document::factory(10)->create();

        // loop thru the documents id
        $documentIds = $documents->map( fn($document) => $document->id);

        // call index endpoint
        $response = $this->json('get', '/api/v1/documents');

        // assert status
        $response->assertStatus(200);

        // verify records
        // json() method allows us to access items from the object via dot notation
        $data = $response->json('data');

        // check if the id of each record exists in the document collection from the factory
        collect($data)->each( fn($document) => $this->assertTrue( in_array($document['id'], $documentIds->toArray()) ) );

        dump( $data );
    }

    public function test_show()
    {
        $dummy = Document::factory()->create();
        $response = $this->json('get', "/api/v1/documents/{$dummy->id}");

        $result = $response->assertStatus(200)->json('data');

        $this->assertEquals( data_get($result, 'id'), $dummy->id, 'Response ID not the same as the model id' );
    }

    public function test_create()
    {
        Event:fake();

        $dummy = Document::factory()->make();

        $response = $this->json('post', '/api/v1/documents', $dummy->toArray());

        $result = $response->assertStatus(201)->json('data');

        // after sending post request, test if an event is dispatched
        Event::assertDispatched(DocumentCreated::class);

        // compare if the document created has the same attribute as the dummy document
        // standardize the result
        $result = collect($result)->only( array_Keys($dummy->getAttributes()) );
        // loop thru the collection and making sure each value exists in the dummy model
        $result->each( function($value, $field) use ($dummy) {
            $this->assertSame( data_get($dummy, $field), $value, 'Fillable is not the same');
        } );
     }

     public function test_update()
     {
        Event::fake();

        $dummy = Document::factory()->create();
        $dummy2 = Document::factory()->make();

        $fillableFields = collect( (new Document())->getFillable() );

        $fillableFields->each( function($toUpdate) use($dummy, $dummy2) {
            $response = $this->json('patch', "/api/v1/documents/{$dummy->id}" , [
                $toUpdate => data_get($dummy2, $toUpdate),
            ]);

            $result = $response->assertStatus(200)->json('data');
            Event::assertDispatched(DocumentUpdated::class);
            $this->assertEquals( data_get($dummy2, $toUpdate), data_get($dummy->refresh(), $toUpdate), 'Failed to update the model');
        } );
     }

     public function test_delete()
     {
        Event::fake();
        $dummy = Document::factory()->create();

        $response = $this->json('delete', "/api/v1/documents/{$dummy->id}");

        $result = $response->assertStatus(200);
        Event::assertDispatched(DocumentDeleted::class);
        $this->expectException(ModelNotFoundException::class);
        Document::query()->findOrFail($dummy->id);
     }
}
