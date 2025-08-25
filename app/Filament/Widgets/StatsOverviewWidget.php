<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Permintaan Pandu', '-'),
//                ->description('32% increase')
//                ->descriptionIcon('heroicon-m-arrow-trending-up')
//                ->color('success'),
            Stat::make('Data RPKRO', '-'),
//                ->description('3 urgent')
//                ->color('warning'),
            Stat::make('SPK Pandu', '-'),
            Stat::make('Data RKBM', '-'),
//                ->description('This month')
//                ->chart([7, 2, 10, 3, 15, 4, 17])
//                ->color('success'),
        ];
    }
}
