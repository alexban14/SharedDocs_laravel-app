<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Document;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    use TruncateTable, DisableForeignKeys;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->disableForeignKeys();
        $this->truncate('comments');

        Comment::factory(3)
            // helper function for creating one document for every comment
            // ->for(Document::factory(1), 'document')
            ->create();

        $this->enableForeignKeys();
    }
}
