<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuthorResource\Pages;
use App\Filament\Resources\AuthorResource\RelationManagers;
use App\Filament\Resources\AuthorResource\RelationManagers\BooksRelationManager;
use App\Models\Author;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class AuthorResource extends Resource
{
    protected static ?string $model = Author::class;

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
    
    public static function canGloballySearch(): bool
    {
        return true;
    }


    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name; 
    }

    protected static ?string $navigationIcon = 'lucide-book-user';

    public static function getFormSchema()
    {
        return [
            Forms\Components\FileUpload::make('image')
                ->image()->avatar()->columnSpanFull(),
            Forms\Components\TextInput::make('name')
                ->required()->columnSpanFull()
                ->maxLength(255),
            Forms\Components\Textarea::make('description')
                ->columnSpanFull(),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->state(function ($record) {

                    return $record->image ?? asset('images/avatar.webp');
                })
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
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

    public static function getRelations(): array
    {
        return [
            BooksRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuthors::route('/'),
            'create' => Pages\CreateAuthor::route('/create'),
            'view' => Pages\ViewAuthor::route('/{record}'),
            'edit' => Pages\EditAuthor::route('/{record}/edit'),
        ];
    }
}
