<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BadUsers;
use Filament\Pages\Page;
use App\Filament\Widgets\BooksByGenreChart;

class CustomDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard';

    protected static ?string $slug = 'dashboard';
    protected static ?string $routeName = 'filament.admin.pages.dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            BooksByGenreChart::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return[
            BadUsers::make(),
        ];
    }
}

