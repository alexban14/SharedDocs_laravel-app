<?php

namespace Database\Seeders;

use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    use TruncateTable, DisableForeignKeys;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // disable foreign key checks
        $this->disableForeignKeys();
        // delete data from a table before seedings
        $this->truncate('users');
        $users = \App\Models\User::factory(10)->create();
        // reenabling foreign key checks again after seeding
        $this->enableForeignKeys();
    }
}
