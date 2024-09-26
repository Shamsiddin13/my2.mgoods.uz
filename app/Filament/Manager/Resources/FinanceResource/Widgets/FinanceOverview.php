<?php

namespace App\Filament\Manager\Resources\FinanceResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinanceOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $manager = auth()->user()->manager; // Replace with the actual manager ID or logic to get it
        $contractorName = auth()->user()->manager; // Replace with the actual contractor name or logic to get it

        $hisoblandi = \DB::selectOne("
        SELECT SUM(AdjustedAmount) AS total
        FROM (
            SELECT
                ID_number,
                displayProductName,
                article,
                source,
                status,
                manager,
                TotalAmount,
                CASE
                    WHEN TotalAmount BETWEEN 0 AND 149999 THEN TotalAmount * 0.05
                    WHEN TotalAmount BETWEEN 150000 AND 399999 THEN TotalAmount * 0.04
                    WHEN TotalAmount BETWEEN 400000 AND 699999 THEN TotalAmount * 0.03
                    WHEN TotalAmount BETWEEN 700000 AND 999999 THEN TotalAmount * 0.02
                    WHEN TotalAmount BETWEEN 1000000 AND 4999999 THEN TotalAmount * 0.01
                END AS AdjustedAmount
            FROM (
                SELECT
                    o.ID_number,
                    o.displayProductName,
                    o.article,
                    o.source,
                    o.target,
                    o.status,
                    o.manager,
                    SUM(o.summ) AS TotalAmount
                FROM orders o
                WHERE
                    o.status = 'sold' AND
                    o.createdAt BETWEEN '2024-08-01 00:00:00' AND NOW()
                GROUP BY
                    o.ID_number,
                    o.displayProductName,
                    o.article,
                    o.source,
                    o.target,
                    o.status,
                    o.manager
            ) subquery
            WHERE
                subquery.manager = ?
        ) final_query
    ", [$manager]);

        $jarayonda = \DB::selectOne("
        SELECT SUM(AdjustedAmount) AS total
        FROM (
            SELECT
                ID_number,
                displayProductName,
                article,
                source,
                status,
                manager,
                TotalAmount,
                CASE
                    WHEN TotalAmount BETWEEN 0 AND 149999 THEN TotalAmount * 0.05
                    WHEN TotalAmount BETWEEN 150000 AND 399999 THEN TotalAmount * 0.04
                    WHEN TotalAmount BETWEEN 400000 AND 699999 THEN TotalAmount * 0.03
                    WHEN TotalAmount BETWEEN 700000 AND 999999 THEN TotalAmount * 0.02
                    WHEN TotalAmount BETWEEN 1000000 AND 4999999 THEN TotalAmount * 0.01
                END AS AdjustedAmount
            FROM (
                SELECT
                    o.ID_number,
                    o.displayProductName,
                    o.article,
                    o.source,
                    o.target,
                    o.status,
                    o.manager,
                    SUM(o.summ) AS TotalAmount
                FROM orders o
                WHERE
                    o.status IN ('send', 'delivered') AND
                    o.createdAt BETWEEN '2024-08-01 00:00:00' AND NOW()
                GROUP BY
                    o.ID_number,
                    o.displayProductName,
                    o.article,
                    o.source,
                    o.target,
                    o.status,
                    o.manager
            ) subquery
            WHERE
                subquery.manager = ?
        ) final_query
    ", [$manager]);

        $yangilar = \DB::selectOne("
        SELECT SUM(AdjustedAmount) AS total
        FROM (
            SELECT
                ID_number,
                displayProductName,
                article,
                source,
                status,
                manager,
                TotalAmount,
                CASE
                    WHEN TotalAmount BETWEEN 0 AND 149999 THEN TotalAmount * 0.05
                    WHEN TotalAmount BETWEEN 150000 AND 399999 THEN TotalAmount * 0.04
                    WHEN TotalAmount BETWEEN 400000 AND 699999 THEN TotalAmount * 0.03
                    WHEN TotalAmount BETWEEN 700000 AND 999999 THEN TotalAmount * 0.02
                    WHEN TotalAmount BETWEEN 1000000 AND 4999999 THEN TotalAmount * 0.01
                END AS AdjustedAmount
            FROM (
                SELECT
                    o.ID_number,
                    o.displayProductName,
                    o.article,
                    o.source,
                    o.target,
                    o.status,
                    o.manager,
                    SUM(o.summ) AS TotalAmount
                FROM orders o
                WHERE
                    o.status IN ('new','accept','recall', 'call_late', 'updated') AND
                    o.createdAt BETWEEN '2024-08-01 00:00:00' AND NOW()
                GROUP BY
                    o.ID_number,
                    o.displayProductName,
                    o.article,
                    o.source,
                    o.target,
                    o.status,
                    o.manager
            ) subquery
            WHERE
                subquery.manager = ?
        ) final_query
    ", [$manager]);

        $tolandi = \DB::selectOne("
        SELECT SUM(t.amount) AS total
        FROM fin_transactions t
        WHERE t.type = 2
        AND t.contractor_name = ?
    ", [$contractorName]);

        $balans = $hisoblandi->total - $tolandi->total;

        $formatNumber = function ($number) {
            if ($number >= 1_000_000) {
                return number_format($number / 1_000_000, 1) . 'M';
            } elseif ($number >= 1_000) {
                return number_format($number / 1_000, 1) . 'K';
            }
            return number_format($number, 0);
        };

        return [
            Stat::make('Hisoblandi', number_format($hisoblandi->total ?? 0, 0, '.', ' '))
                ->color('primary')
                ->icon('heroicon-o-calculator'),

            Stat::make("To'landi", number_format($tolandi->total ?? 0, 0, '.', ' '))
                ->color('warning')
                ->icon('heroicon-o-currency-dollar')
                ->chartColor('warning'),

            Stat::make('Balans', number_format($balans ?? 0, 0, '.', ' '))
                ->color($balans >= 0 ? 'success' : 'danger')
                ->icon($balans >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down'),

            Stat::make('Jarayonda', number_format($jarayonda->total ?? 0, 0, '.', ' '))
                ->color('success')
                ->icon('heroicon-o-truck'),

            Stat::make('Yangilar', number_format($yangilar->total ?? 0, 0, '.', ' '))
                ->color('success')
                ->icon('heroicon-o-sparkles'),
        ];
    }
}

