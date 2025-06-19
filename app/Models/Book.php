<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';

    protected $fillable = [
        'name',
        'description',
        'image',
        'year',
        'publisher_id',
    ];

    protected $appends = [
        'status'
    ];

    public const STATUS_1 = 'available';
    public const STATUS_2 = 'onhands';
    public const STATUS_3 = 'prosrochena';

    public function publisher() 
    {
        return $this->belongsTo(Publisher::class);
    }

    public function categories() 
    {
        return $this->belongsToMany(
            Category::class,
            'book_category',
            'book_id',
            'category_id',
        );
    }

    public function authors() 
    {
        return $this->belongsToMany(
            Author::class,
            'book_author',
            'book_id',
            'author_id',
        );
    }

    public function receipt()
    {
        return $this->hasMany(
            BookReceipt::class,
        );
    }

    public function bookReceipt() 
    {
        return $this->hasMany(BookReceipt::class);
    }

    public function scopeAvailable($query)
    {
        $query->whereDoesntHave('receipt')
            ->orWhereHas('receipt', function ($q) {
                $q->whereRaw('receive_date = (
                    SELECT MAX(r2.receive_date)
                    FROM book_receipts AS r2
                    WHERE r2.book_id = book_receipts.book_id
                )')
                ->whereNotNull('return_date');
            });
    }

    public function getStatusAttribute()
    {
        $lastReceipt = $this->receipt()->orderByDesc('receive_date')->first();
        if(!$lastReceipt || ($lastReceipt && $lastReceipt->return_date)) return self::STATUS_1;
        else {
            if(Carbon::parse($lastReceipt->receive_date)->diffInDays(Carbon::now(), false) <= 30) {
                return self::STATUS_2;
            } else return self::STATUS_3;
        }
    }
}
