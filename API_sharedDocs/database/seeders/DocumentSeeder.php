<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Document;
use App\Models\Comment;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;

use Database\Factories\Helpers\FactoryHelper;

class DocumentSeeder extends Seeder
{
    use TruncateTable, DisableForeignKeys;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // disable foreign key checks
        $this->disableForeignKeys();
        $this->truncate('documents');

        // Document::factory(3)->create();
        // to override a default factory value
        $documents = Document::factory(3)
            // helper function for generating comments from the document seeder
            // ->has(Comment::factory(3), 'comments')
            ->untitled()
            ->create();

        // connecting the documents to their users
        $documents->each( function (Document $doc)
        {
            $doc->users()->sync([FactoryHelper::getRandomModelId(User::class)]);
        });


        // reenabling foreign key checks again after seeding
        $this->enableForeignKeys();

    }
}
