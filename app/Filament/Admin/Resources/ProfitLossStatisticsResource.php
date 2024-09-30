<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProfitLossStatisticsResource\Pages\ListProfitLossStatistics;
use App\Filament\Admin\Resources\ProfitLossStatisticsResource\Pages\UpdateTarget;
use App\Models\Order;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProfitLossStatisticsResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Statistika';
    protected static ?string $pluralModelLabel = "Foyda & Zarar";
    protected static ?string $navigationLabel = "Foyda & Zarar";
    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('displayProductName')
                    ->label("Mahsulot Nomi"),
                TextInput::make('article')
                    ->label("Article"),
                TextInput::make('target')
                    ->label("Harajat summa")
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('displayProductName')
                    ->label('Mahsulot nomi')
                    ->searchable(['o.displayProductName', 'o.article'])
                    ->sortable()
                    ->limit(35)
                    ->tooltip(fn($record) => $record->displayProductName)
                    ->toggleable(),
                TextColumn::make("article")
                    ->label("Article")
                    ->sortable(),
                TextColumn::make('target')
                    ->label("Harajat summa")
                    ->badge()
                    ->color(function ($record) {
                        return $record->target > 0.00 ? 'info' : ($record->target <= 0.00 ? 'gray' : null);
                    })
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('Lead_narxi')
                    ->label("Lead")
                    ->badge()
                    ->color(function ($record) {
                        return $record->Lead_narxi > 0.00 ? 'warning' : ($record->Lead_narxi <= 0.00 ? 'gray' : null);
                    })
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('Qabul_narxi')
                    ->label("Qabul")
                    ->badge()
                    ->color(function ($record) {
                        return $record->Qabul_narxi > 0.00 ? 'info' : ($record->Qabul_narxi <= 0.00 ? 'gray' : null);
                    })
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('Sotildi_narxi')
                    ->label("Sotildi")
                    ->badge()
                    ->color(function ($record) {
                        return $record->Sotildi_narxi > 0.00 ? 'success' : ($record->Sotildi_narxi <= 0.00 ? 'gray' : null);
                    })
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('Profit_Amount')
                    ->label("Profit")
                    ->badge()
                    ->color(function ($record) {
                        return $record->Profit_Amount > 0.00 ? 'success' : ($record->Profit_Amount < 0.00 ? 'danger' : 'gray');
                    })
                    ->toggleable()
                    ->sortable()
                    ->formatStateUsing(fn($state) => number_format($state, 0, '.', ' ')),
                TextColumn::make('YoldaSotildi_narxi')
                    ->label("Yolda + Sotildi")
                    ->badge()
                    ->color(function ($record) {
                        return $record->YoldaSotildi_narxi > 0.00 ? 'info' : ($record->YoldaSotildi_narxi <= 0.00 ? 'gray' : null);
                    })
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('ProfitYolda_Amount')
                    ->label("Profit + Yolda")
                    ->badge()
                    ->color(function ($record) {
                        return $record->ProfitYolda_Amount > 0.00 ? 'success' : ($record->ProfitYolda_Amount < 0.00 ? 'danger' : 'gray');
                    })
                    ->toggleable()
                    ->sortable()
                    ->formatStateUsing(fn($state) => number_format($state, 0, '.', ' ')),
            ])
            ->defaultSort('displayProductName')
            ->paginated([
                10,
                15,
                25,
                40,
                50,
                100,
            ])
            ->filters([
                //
            ])
            ->actions([
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        if (is_null($user)) {
            throw new \Exception("No authenticated user found.");
        }

        $source = $user->source;

        // Build the subquery for counts with alias 'oc'
        $subQuery = Order::select([
            'o.article', // Using alias 'o' for orders
            DB::raw("COUNT(DISTINCT CASE WHEN o.status IN (
            'new', 'updated', 'recall', 'call_late', 'cancel', 'accept',
            'send', 'delivered', 'returned', 'sold'
        ) THEN o.ID_number ELSE NULL END) AS `Lead`"),
            DB::raw("COUNT(DISTINCT CASE WHEN o.status = 'accept' THEN o.ID_number ELSE NULL END) AS `Qabul`"),
            DB::raw("COUNT(DISTINCT CASE WHEN o.status = 'send' THEN o.ID_number ELSE NULL END) AS `Yolda`"),
            DB::raw("COUNT(DISTINCT CASE WHEN o.status = 'delivered' THEN o.ID_number ELSE NULL END) AS `Yetkazildi`"),
            DB::raw("COUNT(DISTINCT CASE WHEN o.status = 'sold' THEN o.ID_number ELSE NULL END) AS `Vipolnen`"),
            DB::raw("COUNT(DISTINCT CASE WHEN o.status = 'returned' THEN o.ID_number ELSE NULL END) AS `QaytibKeldi`"),
            DB::raw("COUNT(DISTINCT CASE WHEN o.status IN ('sold', 'delivered') THEN o.ID_number ELSE NULL END) AS `VipolnenIDostavlen`"),
            DB::raw("COUNT(DISTINCT CASE WHEN o.status = 'send' THEN o.ID_number ELSE NULL END) AS `Vputi`"),
        ])
            ->from('orders as o') // Alias 'o' for orders
            ->where('o.source', $source)
            ->groupBy('o.article');

        // Main query with alias 'o'
        return Order::from('orders as o')
            ->select([
                DB::raw('MAX(o.ID_number) AS ID_number'),
                DB::raw('MAX(o.displayProductName) AS displayProductName'),
                'o.article',
                DB::raw('MAX(o.target) AS target'),
                DB::raw('ROUND(MAX(o.target) / NULLIF(oc.`Lead`, 0), 2) AS Lead_narxi'),
                DB::raw('ROUND(MAX(o.target) / NULLIF((oc.`Qabul` + oc.`Yolda` + oc.`Yetkazildi` + oc.`Vipolnen` + oc.`QaytibKeldi`), 0), 2) AS Qabul_narxi'),
                DB::raw('ROUND(MAX(o.target) / NULLIF((oc.`Yetkazildi` + oc.`Vipolnen`), 0), 2) AS Sotildi_narxi'),
                DB::raw('ROUND(MAX(o.target) / NULLIF((ROUND(oc.`Yolda` / 2, 2) + oc.`Yetkazildi` + oc.`Vipolnen`), 0), 2) AS YoldaSotildi_narxi'),
                DB::raw('((MAX(p.target) * oc.`VipolnenIDostavlen`) - (MAX(o.target) * MAX(u.usd_exchange_rate))) AS Profit_Amount'),
                DB::raw('((MAX(p.target) * (ROUND(oc.`Vputi` / 2, 2) + oc.`VipolnenIDostavlen`)) - (MAX(o.target) * MAX(u.usd_exchange_rate))) AS ProfitYolda_Amount'),
            ])
            ->join('products as p', 'o.article', '=', 'p.article')
            ->joinSub($subQuery, 'oc', function ($join) {
                $join->on('o.article', '=', 'oc.article');
            })
            ->join('users as u', 'o.source', '=', 'u.source')
            ->where('o.source', $source)
            ->groupBy('o.article')
            ->orderBy('o.article');
    }




    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProfitLossStatistics::route('/')
        ];
    }
}
