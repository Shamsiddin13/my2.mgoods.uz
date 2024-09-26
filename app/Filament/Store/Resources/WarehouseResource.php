<?php

namespace App\Filament\Store\Resources;

use App\Filament\Store\Resources\WarehouseResource\Pages;
use App\Models\Product;
use App\Models\WarehouseDetails;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WarehouseResource extends Resource
{
    protected static ?string $model = WarehouseDetails::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static ?string $modelLabel = 'Ombor Statistika';

    protected static ?string $pluralModelLabel = 'Ombor Statistika';
    protected static ?string $navigationGroup = 'Ombor';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $store = auth()->user()->store;

        return WarehouseDetails::select('article')
            ->selectRaw('SUM(CASE WHEN status IN (\'new\') THEN quantity ELSE 0 END) AS Yangi')
            ->selectRaw('SUM(CASE WHEN status IN (\'accept\') THEN quantity ELSE 0 END) AS Qabul')
            ->selectRaw('SUM(CASE WHEN status IN (\'send\', \'delivered\') THEN quantity ELSE 0 END) AS Yolda')
            ->selectRaw('SUM(CASE WHEN status IN (\'delivered\') THEN quantity ELSE 0 END) AS Yetkazildi')
            ->selectRaw('SUM(CASE WHEN status IN (\'returned\') THEN quantity ELSE 0 END) AS QaytibKeldi')
            ->selectRaw('SUM(CASE WHEN status IN (\'sold\') THEN quantity ELSE 0 END) AS Sotildi')
            ->selectRaw('COALESCE((SELECT SUM(amount) FROM warehouse_details WHERE warehouse_details.article = orders.article AND type = \'income\'), 0) AS Kirim')
            ->selectRaw('COALESCE((COALESCE((SELECT SUM(amount) FROM warehouse_details WHERE warehouse_details.article = orders.article AND type = \'income\'), 0) - (SUM(CASE WHEN status IN (\'send\', \'delivered\') THEN quantity ELSE 0 END) + SUM(CASE WHEN status IN (\'sold\') THEN quantity ELSE 0 END))), 0) AS Qoldiq')
            ->from('orders')
            ->where('store', $store)
            ->groupBy('article');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Mahsulot nomi')
                    ->sortable()
                    ->toggleable()
                    ->searchable(['article'])
                    ->getStateUsing(function ($record) {
                        // Assuming $record is an instance of your Landing model and has an 'article' field
                        $product = Product::where('article', $record->article)->first();

                        // Return the product name if found, otherwise return 'No Product'
                        return $product ? $product->name : 'No Product';
                    }),
//                TextColumn::make('article')->label("Artikul")->sortable()->toggleable(),
                TextColumn::make('Kirim')
                    ->sortable()
                    ->badge()
                    ->color(function ($record) {
                        return $record->Kirim > 0 ? 'warning' : ($record->Kirim <= 0 ? 'gray' : null);
                    })
                    ->toggleable(),
                TextColumn::make('Yangi')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('Qabul')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('Yolda')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('Yetkazildi')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('QaytibKeldi')
                    ->label('Qaytib kelgan')
                    ->badge()
                    ->color(function ($record) {
                        return $record->QaytibKeldi <= 0 ? 'gray' : ($record->QaytibKeldi > 0 ? 'danger' : null);
                    })
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('Sotildi')
                    ->sortable()
                    ->badge()
                    ->color(function ($record) {
                        return $record->Sotildi > 0 ? 'success' : ($record->Sotildi <= 0 ? 'gray' : null);
                    })
                    ->toggleable(),
                TextColumn::make('Qoldiq')
                    ->badge()
                    ->color(function ($record) {
                        return $record->Qoldiq > 0 ? 'info' : ($record->Qoldiq <= 0 ? 'gray' : null);
                    })
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('article', 'desc')
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
            ])
            ->bulkActions([
            ]);
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
            'index' => Pages\ListWarehouses::route('/'),
        ];
    }
}
