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
                    ->searchable(['displayProductName', 'article'])
                    ->sortable('displayProductName')
                    ->toggleable(),
                TextColumn::make("article")
                    ->label("Article")
                    ->sortable(),
                TextColumn::make('target')
                    ->label("Harajat summa")
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('Lead_narxi')
                    ->label("Lead")
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('Qabul_narxi')
                    ->label("Qabul")
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('Sotildi_narxi')
                    ->label("Sotildi")
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('Profit_Amount')
                    ->label("Profit")
                    ->toggleable()
                    ->sortable()
                    ->formatStateUsing(fn($state) => number_format($state, 0, '.', ' ')),
                TextColumn::make('YoldaSotildi_narxi')
                    ->label("Yolda + Sotildi")
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('ProfitYolda_Amount')
                    ->label("Profit + Yolda")
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
                'all',
            ])
            ->filters([
                //
            ])
            ->actions([
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $source = auth()->user()->source;

        return Order::from('orders as orders')  // Using 'o orders' alias
        ->selectRaw("
            MAX(orders.ID_number) as ID_number,
            MAX(orders.displayProductName) as displayProductName,
            orders.article as article,
            MAX(orders.target) as target,
            ROUND(MAX(orders.target) / oc.Lead, 2) as Lead_narxi,
            ROUND(MAX(orders.target) / (oc.Qabul + oc.Yolda + oc.Yetkazildi + oc.Vipolnen + oc.QaytibKeldi), 2) as Qabul_narxi,
            ROUND(MAX(orders.target) / (oc.Yetkazildi + oc.Vipolnen), 2) as Sotildi_narxi,
            ROUND(MAX(orders.target) / (ROUND(oc.Yolda / 2, 2) + oc.Yetkazildi + oc.Vipolnen), 2) as YoldaSotildi_narxi,
            ((MAX(p.target) * oc.VipolnenIDostavlen) - (MAX(orders.target) * MAX(u.kurs))) as Profit_Amount,
            ((MAX(p.target) * (ROUND(oc.Vputi / 2, 2) + oc.VipolnenIDostavlen)) - (MAX(orders.target) * MAX(u.kurs))) as ProfitYolda_Amount
        ")
            ->join('products as p', 'orders.article', '=', 'p.article')  // Joining products table with alias `p`
            ->join(DB::raw("
            (SELECT
                article,
                COUNT(DISTINCT CASE WHEN status IN ('Новый', 'Принят', 'Недозвон', 'Отмена', 'В пути', 'Доставлен', 'Выполнен', 'Возврат', 'Подмены') THEN ID_number ELSE NULL END) AS Lead,
                COUNT(DISTINCT CASE WHEN status = 'Принят' THEN ID_number ELSE NULL END) AS Qabul,
                COUNT(DISTINCT CASE WHEN status IN ('В пути', 'EMU') THEN ID_number ELSE NULL END) AS Yolda,
                COUNT(DISTINCT CASE WHEN status IN ('Доставлен') THEN ID_number ELSE NULL END) AS Yetkazildi,
                COUNT(DISTINCT CASE WHEN status IN ('Выполнен') THEN ID_number ELSE NULL END) AS Vipolnen,
                COUNT(DISTINCT CASE WHEN status IN ('Возврат') THEN ID_number ELSE NULL END) AS QaytibKeldi,
                COUNT(DISTINCT CASE WHEN status IN ('Выполнен', 'Доставлен') THEN ID_number ELSE NULL END) AS VipolnenIDostavlen,
                COUNT(DISTINCT CASE WHEN status IN ('В пути') THEN ID_number ELSE NULL END) AS Vputi
             FROM orders
             WHERE source = '$source'
             GROUP BY article
            ) as oc"), 'orders.article', '=', 'oc.article')  // Subquery alias `oc`
            ->join('users as u', 'orders.source', '=', 'u.source')  // Joining users table with alias `u`
            ->where('orders.source', '=', $source)  // Filtering by source
            ->groupBy('orders.article');  // Group by the `o.article`
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
            'index' => ListProfitLossStatistics::route('/'),
//            'create' => Pages\CreateProfitLossStatistics::route('/create'),
            'update-target' => UpdateTarget::route('/update-target'),
        ];
    }
}
