<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];

        protected $hidden = [
        'password',
    ];
    
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    
    protected static function boot()
    {
        parent::boot();
        self::created(function ($user) {
            ReaderCard::query()->create([
                'user_id' => $user->id
            ]);
        });
    }

    public function readerCard()
    {
        return $this->hasOne(ReaderCard::class);
    }

    public function bookReceipts() 
    {
        return $this->hasManyThrough(
            BookReceipt::class,
            ReaderCard::class,
        );
    }
}
