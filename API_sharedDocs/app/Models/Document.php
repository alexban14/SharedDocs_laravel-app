<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    // casting the body of the document from json to an array
    // so we don't need to run the body field thru encode/decode functions
    protected $casts = [
        'body' => 'array'
    ];

    // storing the title in uppercase via an accessor
    public function getTitleUpperCaseAttribute()
    {
        return strtoupper($this->title);
    }

    // storing the title provided as a function argument in lowercase via mutator
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = strtolower($value);
    }


    // making a connection to the Comment model via one to many relationship
    public function comments()
    {
        return $this->hasMany(Comment::class, 'document_id');
    }

    // making a connection to the User model via many to many relationship
    public function users()
    {
        return $this->belongsToMany(User::class, 'document_user', 'document_id', 'user_id');
    }
}
