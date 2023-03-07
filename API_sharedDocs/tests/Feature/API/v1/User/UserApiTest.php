<?php

namespace Tests\Feature\API\v1\User;

use App\Events\Models\User\UserCreated;
use App\Events\Models\User\UserDeleted;
use App\Events\Models\User\UserUpdated;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase; // a trait that resets all the data from the db

    // runs before a test is executed to replicate user registration
    public function setup(): void
    {
        parent::setup();
        $user = User::factory()->make();
        // accepts a second argument if you want to specify any auth guards
        $this->actingAs($user);
    }

    public function test_index()
    {
        // load data in the database
        $user = User::factory(10)->create();

        // loop thru the documents id
        $userIds = $user->map( fn($user) => $user->id);

        // call index endpoint
        $response = $this->json('get', '/api/v1/users');

        // assert status
        $response->assertStatus(200);

        // verify records
        // json() method allows us to access items from the object via dot notation
        $data = $response->json('data');

        // check if the id of each record exists in the comment collection from the factory
        collect($data)->each( fn($user) => $this->assertTrue( in_array($user['id'], $userIds->toArray()) ) );

        dump( $data );
    }

    public function test_show()
    {
        $dummy = User::factory()->create();
        $response = $this->json('get', "/api/v1/users/{$dummy->id}");

        $result = $response->assertStatus(200)->json('data');

        $this->assertEquals( data_get($result, 'id'), $dummy->id, 'Response ID not the same as the model id' );
    }

    public function test_create()
    {
        Event:fake();

        $dummy = User::factory()->make();

        $response = $this->json('post', '/api/v1/users', $dummy->toArray());

        $result = $response->assertStatus(201)->json('data');

        // after sending post request, test if an event is dispatched
        Event::assertDispatched(UserCreated::class);

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

        $dummy = User::factory()->create();
        $dummy2 = User::factory()->make();

        $fillableFields = collect( (new User())->getFillable() );

        $fillableFields->each( function($toUpdate) use($dummy, $dummy2) {
            $response = $this->json('patch', "/api/v1/users/{$dummy->id}" , [
                $toUpdate => data_get($dummy2, $toUpdate),
            ]);

            $result = $response->assertStatus(200)->json('data');
            Event::assertDispatched(UserUpdated::class);
            $this->assertEquals( data_get($dummy2, $toUpdate), data_get($dummy->refresh(), $toUpdate), 'Failed to update the model');
        } );
     }

     public function test_delete()
     {
        Event::fake();
        $dummy = User::factory()->create();

        $response = $this->json('delete', "/api/v1/users/{$dummy->id}");

        $result = $response->assertStatus(200);
        Event::assertDispatched(UserDeleted::class);
        $this->expectException(ModelNotFoundException::class);
        User::query()->findOrFail($dummy->id);
     }
}
