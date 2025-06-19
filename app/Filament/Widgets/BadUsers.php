<?php

namespace App\Filament\Widgets;

use App\Models\BookReceipt;
use App\Models\User;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class BadUsers extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        $nDaysAgo = now()->subDays(30);
        return $table
            ->query(
                BookReceipt::whereDate('receive_date', '<=', $nDaysAgo)->whereNull('return_date')
            )
            ->columns([
                TextColumn::make('user.name')->label('Імʼя'),
                TextColumn::make('user.email'),
                TextColumn::make('receive_date')->date('d-m-Y H:i'),
                TextColumn::make('days_count')->state(function ($record) {
                    // Розраховуємо кількість днів із receive_date до сьогодні
                    return round(Carbon::parse($record->receive_date)->diffInDays(Carbon::now(), false)-30);
                })
                ->formatStateUsing(function ($state) {
                    return "Протерміновано на {$state} днів";
                })
            ]);
    }
}
