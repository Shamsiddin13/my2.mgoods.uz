<?php

namespace App\Filament\Store\Resources\FinanceResource\Widgets;

use App\Filament\Store\Resources\FinanceResource\Pages\ListFinances;
use App\Models\Order;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class FinanceOverview extends BaseWidget
{
    protected function getTablePage(): string
    {
        return ListFinances::class;
    }
    protected function getStats(): array
    {
        $store = auth()->user()->store;
        $contractorName = $store; // Assuming the contractor name is same as the store for `tolandi`

        // 1. Jarayonda Query
        $jarayondaQuery = Order::join('products', 'orders.article', '=', 'products.article')
            ->whereIn('orders.status', ['В пути', 'Доставлен'])
            ->where('orders.store', ucfirst($store))
            ->selectRaw('COUNT(DISTINCT orders.ID_number) * MAX(products.buyPrice) AS TotalStore');

        $jarayonda = DB::table(DB::raw("({$jarayondaQuery->toSql()}) as jarayonda_subquery"))
            ->mergeBindings($jarayondaQuery->getQuery())
            ->selectRaw('COALESCE(SUM(TotalStore), 0) as Jarayonda')
            ->value('Jarayonda');

        // 2. Hisoblandi Query
        $hisoblandiQuery = Order::join('products', 'orders.article', '=', 'products.article')
            ->where('orders.status', 'Выполнен')
            ->where('orders.store', ucfirst($store))
            ->selectRaw('COUNT(DISTINCT orders.ID_number) * MAX(products.buyPrice) AS TotalStore');

        $hisoblandi = DB::table(DB::raw("({$hisoblandiQuery->toSql()}) as hisoblandi_subquery"))
            ->mergeBindings($hisoblandiQuery->getQuery())
            ->selectRaw('COALESCE(SUM(TotalStore), 0) as Hisoblandi')
            ->value('Hisoblandi');

        // 3. Tolandi Query
        $tolandi = Transaction::where('type', 2)
            ->where('contractor_name', $contractorName)
            ->sum('amount');

        // 4. Balans Calculation
        $balans = $hisoblandi - $tolandi;

        // Return the stats for Filament
        return [
            Stat::make('Hisoblandi', number_format($hisoblandi, 0, '.', ' '))
                ->color('primary')
                ->icon('heroicon-o-calculator'),

            Stat::make("To'landi", number_format($tolandi, 0, '.', ' '))
                ->color('warning')
                ->icon('heroicon-o-currency-dollar')
                ->chartColor('warning'),

            Stat::make('Balans', number_format($balans, 0, '.', ' '))
                ->color($balans >= 0 ? 'success' : 'danger')
                ->icon($balans >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down'),

            Stat::make('Jarayonda', number_format($jarayonda, 0, '.', ' '))
                ->color('success')
                ->icon('heroicon-o-truck'),
        ];
    }
}
