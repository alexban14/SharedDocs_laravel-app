<?php

namespace Database\Seeders;

use App\Models\Document;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;

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
        Document::factory(3)->create();

        // to override a default factory value
        Document::factory(3)->untitled()->create();
        // reenabling foreign key checks again after seeding
        $this->enableForeignKeys();
    }
}
