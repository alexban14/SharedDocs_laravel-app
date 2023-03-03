<?php

namespace Tests\Feature;

use App\Events\Comment\CommentCreated;
use App\Events\Comment\CommentDeleted;
use App\Events\Comment\CommentUpdated;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CommentApiTest extends TestCase
{
    use RefreshDatabase; // a trait that resets all the data from the db

    public function test_index()
    {
        // load data in the database
        $comments = Comment::factory(10)->create();

        // loop thru the documents id
        $commentIds = $comments->map( fn($comment) => $comment->id);

        // call index endpoint
        $response = $this->json('get', '/api/v1/comments');

        // assert status
        $response->assertStatus(200);

        // verify records
        // json() method allows us to access items from the object via dot notation
        $data = $response->json('data');

        // check if the id of each record exists in the comment collection from the factory
        collect($data)->each( fn($comment) => $this->assertTrue( in_array($comment['id'], $commentIds->toArray()) ) );

        dump( $data );
    }

    public function test_show()
    {
        $dummy = Comment::factory()->create();
        $response = $this->json('get', "/api/v1/comments/{$dummy->id}");

        $result = $response->assertStatus(200)->json('data');

        $this->assertEquals( data_get($result, 'id'), $dummy->id, 'Response ID not the same as the model id' );
    }

    public function test_create()
    {
        // Event:fake();

        $dummy = Comment::factory()->make();

        $response = $this->json('post', '/api/v1/comments', $dummy->toArray());

        $result = $response->assertStatus(201)->json('data');

        // after sending post request, test if an event is dispatched
        // Event::assertDispatched(CommentCreated::class);

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
        // Event::fake();

        $dummy = Comment::factory()->create();
        $dummy2 = Comment::factory()->make();

        $fillableFields = collect( (new Comment())->getFillable() );

        $fillableFields->each( function($toUpdate) use($dummy, $dummy2) {
            $response = $this->json('patch', "/api/v1/comments/{$dummy->id}" , [
                $toUpdate => data_get($dummy2, $toUpdate),
            ]);

            $result = $response->assertStatus(200)->json('data');
            // Event::assertDispatched(CommentUpdated::class);
            $this->assertEquals( data_get($dummy2, $toUpdate), data_get($dummy->refresh(), $toUpdate), 'Failed to update the model');
        } );
     }

     public function test_delete()
     {
        // Event::fake();
        $dummy = Comment::factory()->create();

        $response = $this->json('delete', "/api/v1/comments/{$dummy->id}");

        $result = $response->assertStatus(200);
        // Event::assertDispatched(CommentDeleted::class);
        $this->expectException(ModelNotFoundException::class);
        Comment::query()->findOrFail($dummy->id);
     }
}