<?php

namespace Tests\Unit;


// use PHPUnit\Framework\TestCase;
use App\Exceptions\GeneralJsonException;
use App\Models\User;
use App\Models\Document;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use Tests\TestCase; // test class provided by laravel that gets you access to an app instance

class CommentRepositoryTest extends TestCase
{
    // THESE ARE HAPPY TEST CASES

    // snake_case_name_convention for test names
    public function test_create()
    {
        // 1. Define the goal
        // test if create() actually creates a comment

        // 2. Replicate the env / restriction
        $repository = $this->app->make(CommentRepository::class);

        $user = User::factory()->create();
        $document = Document::factory()->create();

        // 3. Define the source of truth
        $payload = [
            'body' => ['something'],
            'user_id' => $user->id,
            'document_id' => $document->id,
        ];

        // 4. Compare the result
        $result = $repository->create($payload);

        // method that compares two values using ===
        $this->assertSame($payload['document_id'], $result->document_id, 'Comment creation didn\'t work');
    }

    public function test_update()
    {
        // Goal: update the post using the update method

        // env
        $repository = $this->app->make(CommentRepository::class);

        $dummyComment = Comment::factory()->create();

        // source of truth
        $payload = [
            'body' => ['great documentation'],
        ];

        // compare result
        $updated = $repository->update($dummyComment, $payload);
        // method that compares two values using ===
        $this->assertSame($payload['body'], $updated->body, 'Comment update didn\'t work');
    }

    public function test_delete()
    {
        //  goal: test the delete method

        // env
        $repository = $this->app->make(CommentRepository::class);
        $dummyComment = Comment::factory(1)->create()->first();

        // compare result
        $deleted = $repository->forceDelete($dummyComment);
        // verify if deleted
        $found = Comment::query()->find($dummyComment->id);

        $this->assertSame(null, $found, 'Comment wasn\'t delete');
    }

    // EDGE CASES TEST CASES - the ones that we actually need to cover
    public function test_delete_will_throw_exception_when_delete_comment_that_doesnt_exist()
    {
        // env
        $repository = $this->app->make(CommentRepository::class);
        $dummyComment = Comment::factory(1)->make()->first();

        $this->expectException(GeneralJsonException::class);
        $deleted = $repository->forceDelete($dummyComment);
    }
}
