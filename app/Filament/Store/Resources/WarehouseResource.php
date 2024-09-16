<?php

namespace App\Filament\Store\Resources;

use App\Filament\Store\Resources\WarehouseResource\Pages;
use App\Models\Product;
use App\Models\Warehouse;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class WarehouseResource extends Resource
{
    protected static ?string $model = Warehouse::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
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

        return Warehouse::select('article')
            ->selectRaw('SUM(CASE WHEN status IN (\'Новый\') THEN quantity ELSE 0 END) AS Yangi')
            ->selectRaw('SUM(CASE WHEN status IN (\'Принят\') THEN quantity ELSE 0 END) AS Qabul')
            ->selectRaw('SUM(CASE WHEN status IN (\'В пути\', \'Доставлен\') THEN quantity ELSE 0 END) AS Yolda')
            ->selectRaw('SUM(CASE WHEN status IN (\'Доставлен\') THEN quantity ELSE 0 END) AS Yetkazildi')
            ->selectRaw('SUM(CASE WHEN status IN (\'Возврат\',\'Подмены\') THEN quantity ELSE 0 END) AS QaytibKeldi')
            ->selectRaw('SUM(CASE WHEN status IN (\'Выполнен\') THEN quantity ELSE 0 END) AS Sotildi')
            ->selectRaw('COALESCE((SELECT SUM(amount) FROM warehouse WHERE warehouse.article = orders.article AND type = \'income\'), 0) AS Kirim')
            ->selectRaw('COALESCE((COALESCE((SELECT SUM(amount) FROM warehouse WHERE warehouse.article = orders.article AND type = \'income\'), 0) - (SUM(CASE WHEN status IN (\'В пути\', \'Доставлен\') THEN quantity ELSE 0 END) + SUM(CASE WHEN status IN (\'Выполнен\') THEN quantity ELSE 0 END))), 0) AS Qoldiq')
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
                TextColumn::make('Kirim')->sortable()->toggleable(),
                TextColumn::make('Yangi')->sortable()->toggleable(),
                TextColumn::make('Qabul')->sortable()->toggleable(),
                TextColumn::make('Yolda')->sortable()->toggleable(),
                TextColumn::make('Yetkazildi')->sortable()->toggleable(),
                TextColumn::make('QaytibKeldi')->label('Qaytib kelgan')->sortable()->toggleable(),
                TextColumn::make('Sotildi')->sortable()->toggleable(),
                TextColumn::make('Qoldiq')->sortable()->toggleable(),
            ])
            ->defaultSort('article', 'desc')
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
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
//            'create' => Pages\CreateWarehouse::route('/create'),
//            'edit' => Pages\EditWarehouse::route('/{record}/edit'),
        ];
    }
}
