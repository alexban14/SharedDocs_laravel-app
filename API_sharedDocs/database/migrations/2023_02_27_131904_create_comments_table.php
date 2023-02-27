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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->json(column: 'body')->nullable();
            $table->foreignId(column: 'user_id');
            $table->foreign(columns: 'user_id')->on(table: 'users')->references(column: 'id')->cascadeOnDelete();
            $table->foreignId(column: 'document_id');
            $table->foreign(columns: 'document_id')->on(table: 'documents')->references(column: 'id')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
