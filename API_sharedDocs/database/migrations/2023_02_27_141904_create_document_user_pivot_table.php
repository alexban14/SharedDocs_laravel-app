<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_user', function (Blueprint $table) {
            $table->foreignId(column: 'user_id')->index();
            $table->foreign(columns: 'user_id')->on(table: 'users')->references(column: 'id')->cascadeOnDelete();
            $table->foreignId(column: 'document_id');
            $table->foreign(columns: 'document_id')->on(table: 'documents')->references(column: 'id')->cascadeOnDelete();
            $table->primary(['document_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_user');
    }
};
