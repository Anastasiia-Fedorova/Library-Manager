<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $table = 'authors';

    protected $fillable = [
        'name',
        'image',
        'description',
    ];

    public function books() 
    {
        return $this->belongsToMany(
            Book::class,
            'book_author',
            'author_id',
            'book_id',
        );
    }
}
