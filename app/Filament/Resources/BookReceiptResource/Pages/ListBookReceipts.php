<?php

namespace App\Filament\Resources\BookReceiptResource\Pages;

use App\Filament\Resources\BookReceiptResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookReceipts extends ListRecords
{
    protected static string $resource = BookReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
