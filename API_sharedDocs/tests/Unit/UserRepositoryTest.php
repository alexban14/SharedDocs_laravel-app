<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use App\Exceptions\GeneralJsonException;
use App\Models\User;
use App\Repositories\UserRepository;
use Tests\TestCase; // test class provided by laravel that gets you access to an app instance


class UserRepositoryTest extends TestCase
{
    // snake_case_name_convention for test names
    public function test_create()
    {
        // 1. Define the goal
        // test if create() actually creates a user

        // 2. Replicate the env / restriction
        $repository = $this->app->make(UserRepository::class);

        // 3. Define the source of truth
        $payload = [
            'name' => 'misu',
            'email' => 'misulica@lica.com',
            'password' => 'misumisumisu',
        ];

        // 4. Compare the result
        $result = $repository->create($payload);

        // method that compares two values using ===
        $this->assertSame($payload['name'], $result->name, 'User creation didn\'t work');
    }

    public function test_update()
    {
        // Goal: update the user using the update method

        // env
        $repository = $this->app->make(UserRepository::class);

        // grabbing the first element from the result of the create method
        $dummyDocument = User::factory(1)->create()[0];

        // source of truth
        $payload = [
            'name' => 'nico',
            'email' => 'nico@nicoleta.com',
            'password' => 'niconiconico',
        ];

        // compare result
        $updated = $repository->update($dummyDocument, $payload);
        // method that compares two values using ===
        $this->assertSame($payload['name'], $updated->name, 'User update didn\'t work');
    }

    public function test_delete()
    {
        //  goal: test the delete method

        // env
        $repository = $this->app->make(UserRepository::class);
        $dummyDocument = User::factory(1)->create()->first();

        // compare result
        $deleted = $repository->forceDelete($dummyDocument);
        // verify if deleted
        $found = User::query()->find($dummyDocument->id);

        $this->assertSame(null, $found, 'User wasn\'t delete');
    }

    // EDGE CASES TEST CASES - the ones that we actually need to cover
    public function test_delete_will_throw_exception_when_delete_user_that_doesnt_exist()
    {
        // env
        $repository = $this->app->make(UserRepository::class);
        $dummyDocument = User::factory(1)->create()->first();

        $this->expectException(GeneralJsonException::class);
        $deleted = $repository->forceDelete($dummyDocument);
    }
}
