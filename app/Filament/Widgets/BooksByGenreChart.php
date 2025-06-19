<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class BooksByGenreChart extends ChartWidget
{
    protected static ?string $heading = 'Кількість книг за категоріями';

    protected function getData(): array
    {
        $categories = Category::withCount('books')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Кількість книг',
                    'data' => $categories->pluck('books_count'),
                    'backgroundColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40',
                        '#C9CBCF',
                        '#8BC34A',
                        '#03A9F4',
                        '#E91E63',
                    ],
                ],
            ],
            'labels' => $categories->pluck('name'),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

}