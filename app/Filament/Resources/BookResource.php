<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

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


    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image')
                    ->image()->columnSpanFull()
                    ->panelLayout('grid'),
                Forms\Components\TextInput::make('name')
                    ->required()->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('year')
                    ->numeric()
                    ->default(null),
                Select::make('publisher_id')->native(false)
                    ->relationship(name: 'publisher', titleAttribute: 'name'),
                Select::make('authors')
                    ->label('Authors')
                    ->relationship('authors', 'name')
                    ->multiple()->columnSpanFull()
                    ->preload()
                    ->createOptionForm(AuthorResource::getFormSchema()),
                Select::make('categories')
                    ->label('Categories')
                    ->relationship('categories', 'name')
                    ->multiple()->columnSpanFull()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->filters([
                SelectFilter::make('authors')
                    ->relationship('authors', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('categories')
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')->options([
                    Book::STATUS_1 => Str::convertCase(Book::STATUS_1, 2),
                    Book::STATUS_2 => Str::convertCase(Book::STATUS_2, 2),
                    Book::STATUS_3 => Str::convertCase(Book::STATUS_3, 2),
                ])
                    ->multiple(),
                SelectFilter::make('publisher')
                    ->relationship('publisher', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
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
            Tables\Columns\TextColumn::make('name')
                ->searchable(),
            // Tables\Columns\TextColumn::make('authors.name')
            //     ->searchable(),
            Tables\Columns\TextColumn::make('authors')
            ->label('Authors')
            ->formatStateUsing(function ($state, $record) {
                return $record->authors
                    ->map(fn ($author) => 
                        '<a href="' . route('filament.admin.resources.authors.view', $author->id) . '" target="_blank">' 
                        . e($author->name) . 
                        '</a>'
                    )
                    ->implode(', ');
            })
            ->html()
            ->alignCenter(),
            Tables\Columns\ImageColumn::make('image'),
            Tables\Columns\TextColumn::make('year')
                ->sortable(),
            Tables\Columns\TextColumn::make('publisher.name'),
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'onhands' => 'gray',
                    'available' => 'success',
                    'prosrochena' => 'danger',
                }),
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
            // CategoriesRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'view' => Pages\ViewBook::route('/{record}'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
