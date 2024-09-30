<?php

namespace App\Filament\Manager\Resources\FinanceResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinanceOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Get the current user's ID
        $userId = auth()->user()->id;

        // Fetch the user report data from the database
        $userReport = \DB::table('users_reports')
            ->select('total', 'hold', 'paid')
            ->where('user_id', $userId)
            ->first();

        // If no report is found, set default values
        if (!$userReport) {
            $hisoblandi = 0;
            $tolandi = 0;
            $jarayonda = 0;
        } else {
            $hisoblandi = $userReport->total;
            $tolandi = $userReport->paid;
            $jarayonda = $userReport->hold;
        }

        // Calculate the balance
        $balans = $hisoblandi - $tolandi;

        return [
            Stat::make('Hisoblandi', number_format($hisoblandi, 0, '.', ' '))
                ->color('primary')
                ->icon('heroicon-o-calculator'), // Example icon for 'Hisoblandi'

            Stat::make("To'landi", number_format($tolandi, 0, '.', ' '))
                ->color('warning')
                ->icon('heroicon-o-currency-dollar')
                ->chartColor('warning'), // Example icon for 'Tolandi'

            Stat::make('Balans', number_format($balans, 0, '.', ' '))
                ->color($balans >= 0 ? 'success' : 'danger')
                ->icon($balans >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down'), // Upward arrow for positive balance, downward for negative

            Stat::make('Jarayonda', number_format($jarayonda, 0, '.', ' '))
                ->color('success')
                ->icon('heroicon-o-truck'), // Example icon for 'Jarayonda'
        ];
    }
}

