<?php

namespace App\Filament\Resources\BookReceiptResource\Pages;

use App\Filament\Resources\BookReceiptResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookReceipt extends EditRecord
{
    protected static string $resource = BookReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
