<?php

namespace App\Filament\Resources\TransactionResource\Widgets;

use App\Filament\Resources\TransactionResource\Pages\ListTransactions;
use App\Models\Order;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class TransactionOverview extends BaseWidget
{
    protected static ?string $pollingInterval = null;
    protected function getTablePage(): string
    {
        return ListTransactions::class;
    }
    protected function getStats(): array
    {
        $source = 'btd';
        $contractorName = 'btd';

        // 1. Jarayonda Query (Status: 'В пути', 'Доставлен')
        $jarayondaQuery = Order::join('products', 'orders.article', '=', 'products.article')
            ->whereIn('orders.status', ['В пути', 'Доставлен'])
            ->where('orders.source', $source)
            ->selectRaw('COUNT(DISTINCT orders.ID_number) * products.target AS TotalTarget')
            ->groupBy('orders.article', 'products.target');

        $jarayonda = DB::table(DB::raw("({$jarayondaQuery->toSql()}) as jarayonda_subquery"))
            ->mergeBindings($jarayondaQuery->getQuery()) // Merge bindings to avoid issues with bindings.
            ->selectRaw('COALESCE(SUM(TotalTarget), 0) as Jarayonda')
            ->value('Jarayonda'); // Get the value of the query directly.

        // 2. Hisoblandi Query (Status: 'Выполнен')
        $hisoblandiQuery = Order::join('products', 'orders.article', '=', 'products.article')
            ->where('orders.status', 'Выполнен')
            ->where('orders.source', $source)
            ->selectRaw('COUNT(DISTINCT orders.ID_number) * products.target AS TotalTarget')
            ->groupBy('orders.article', 'products.target');

        $hisoblandi = DB::table(DB::raw("({$hisoblandiQuery->toSql()}) as hisoblandi_subquery"))
            ->mergeBindings($hisoblandiQuery->getQuery())
            ->selectRaw('COALESCE(SUM(TotalTarget), 0) as Hisoblandi')
            ->value('Hisoblandi');

        // 3. Tolandi Query (Transaction Type: 2)
        $tolandi = Transaction::where('type', 2)
            ->where('contractor_name', $contractorName)
            ->sum('amount');

        // 4. Balans Calculation (Hisoblandi - Tolandi)
        $balans = $hisoblandi - $tolandi;

        return [
            Stat::make('Jarayonda', number_format($jarayonda, 0, '.', ' '))
                ->color('success')
                ->icon('heroicon-o-truck'), // Example icon for 'Jarayonda'

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
        ];
    }
}
