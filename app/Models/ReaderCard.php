<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ReaderCard extends Model
{
    protected $table = 'reader_cards';

    protected $fillable = [
        'card_number',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($readerCard) {
            $readerCard->card_number = Str::uuid();
        });
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function bookReceipts() 
    {
        return $this->hasMany(BookReceipt::class);
    }
}
