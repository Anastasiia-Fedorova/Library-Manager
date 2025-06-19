<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookReceiptResource\Pages;
use App\Filament\Resources\BookReceiptResource\RelationManagers;
use App\Models\Book;
use App\Models\BookReceipt;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookReceiptResource extends Resource
{
    protected static ?string $model = BookReceipt::class;

    protected static ?string $navigationIcon = 'mdi-receipt-text-clock-outline';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([              
                Select::make('book_id')
                    ->label('Book')
                    ->searchable()
                    ->relationship(
                        name: 'book',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query->available()
                    )
                    ->getOptionLabelFromRecordUsing(function (Book $book) { //$record = this.model
                        return $book ? "{$book?->name} - {$book?->authors()?->first()?->name}" : 'Book not found';
                    })
                    ->preload(),          
                Select::make('reader_card_id')
                    ->label('User')
                    ->searchable()
                    ->relationship('readerCard', 'readerCard.id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->user->name} - {$record->card_number}" ?? '-')
                    ->preload(),
                Forms\Components\DateTimePicker::make('receive_date')
                    ->required(),
                Forms\Components\DateTimePicker::make('return_date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getTableColumns())
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getTableColumns() 
    {
        return [
                Tables\Columns\TextColumn::make('book.name') 
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('author') 
                    ->getStateUsing(function (BookReceipt $bookReceipt) { //$record = this.model
                        return "{$bookReceipt->book?->authors()?->first()?->name}" ?? 'Author  not found';
                    })
                    ->searchable(),
                    Tables\Columns\TextColumn::make('user_id')->label('User')
                    ->getStateUsing(function (BookReceipt $bookReceipt) {  //$record = this.model
                        return "{$bookReceipt->user->name}" ?? '-';
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('reader_card_id')->label('Reader card')
                    ->getStateUsing(function (BookReceipt $bookReceipt) {  //$record = this.model
                        return "{$bookReceipt->readerCard?->card_number}" ?? '-';
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('receive_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('return_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookReceipts::route('/'),
            'create' => Pages\CreateBookReceipt::route('/create'),
            'view' => Pages\ViewBookReceipt::route('/{record}'),
            'edit' => Pages\EditBookReceipt::route('/{record}/edit'),
        ];
    }
}
