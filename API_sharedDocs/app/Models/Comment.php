<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // casting the body of the document from json to an array
    // so we don't need to run the body field thru encode/decode functions
    protected $casts = [
        'body' => 'array'
    ];

    // making a connection to other Document model via one to one relationship
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    // making a connection to the USer model via one to one relationship
    public function comments()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
