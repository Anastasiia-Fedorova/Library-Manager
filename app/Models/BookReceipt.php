<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookReceipt extends Model
{
    protected $table = 'book_receipts';

    protected $fillable = [
        'book_id',
        'reader_card_id',
        'receive_date',
        'return_date',
    ];

    protected $casts =  [
        'receive_date' => 'datetime',
        'return_date' => 'datetime'
    ];

    public function book() 
    {
        return $this->belongsTo(Book::class);
    }

    public function readerCard() 
    {
        return $this->belongsTo(ReaderCard::class);
    }

    public function user() 
    {
        return $this->hasOneThrough(
            User::class,
            ReaderCard::class,
            'id', // Foreign key on the reader_cards table...
            'id', // Foreign key on the users table...
            'reader_card_id', // Local key on the book_receipts table...
            'user_id', // Local key on the reader_cards table...
        );
    }
}
