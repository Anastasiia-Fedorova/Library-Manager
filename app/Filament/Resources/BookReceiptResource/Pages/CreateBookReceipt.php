<?php

namespace App\Filament\Resources\BookReceiptResource\Pages;

use App\Filament\Resources\BookReceiptResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBookReceipt extends CreateRecord
{
    protected static string $resource = BookReceiptResource::class;
}
