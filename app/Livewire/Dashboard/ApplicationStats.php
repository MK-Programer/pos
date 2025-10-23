<?php

namespace App\Livewire\Dashboard;

use App\Models\Item;
use App\Models\Sale;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ApplicationStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Items', Item::count()),
            Stat::make('Total Users',User::count()),
            Stat::make('Total Sales',Sale::count()),
        ];
    }
}
