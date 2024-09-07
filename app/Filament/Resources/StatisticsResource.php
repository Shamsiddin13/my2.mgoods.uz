<?php

namespace App\Filament\Resources;

use App\Models\Order;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\StatisticsResource\Pages;

class StatisticsResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $pluralModelLabel = "Statistika";
    protected static ?string $navigationLabel = "Statistika";
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        // Define the source and date range you need to filter by
        $source = auth()->user()->source;

        // Call the getOrderStatistics method
        return Order::selectRaw("
            article,
            COUNT(DISTINCT CASE WHEN status IN ('Новый', 'Принят', 'Недозвон', 'Отмена', 'В пути', 'Доставлен', 'Выполнен', 'Возврат', 'Подмены') THEN ID_number ELSE NULL END) AS Lead,
            COUNT(DISTINCT CASE WHEN status IN ('Принят') THEN ID_number ELSE NULL END) AS Qabul,
            COUNT(DISTINCT CASE WHEN status IN ('Отмена') THEN ID_number ELSE NULL END) AS Otkaz,
            COUNT(DISTINCT CASE WHEN status IN ('В пути', 'EMU') THEN ID_number ELSE NULL END) AS Yolda,
            COUNT(DISTINCT CASE WHEN status IN ('Доставлен') THEN ID_number ELSE NULL END) AS Yetkazildi,
            COUNT(DISTINCT CASE WHEN status IN ('Выполнен') THEN ID_number ELSE NULL END) AS Sotildi,
            COUNT(DISTINCT CASE WHEN status IN ('Возврат') THEN ID_number ELSE NULL END) AS QaytibKeldi
        ")
            ->where('source', '=', $source)
            ->groupBy('article')  // Grouping only by article
            ->orderBy('article', 'DESC');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Sana')
                    ->label('Sana')
                    ->sortable()
                    ->date(),
                TextColumn::make('Article')
                    ->label('Article')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('target')
                    ->label('Target')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('Lead')
                    ->label('Lead')
                    ->sortable(),
                TextColumn::make('Qabul')
                    ->label('Qabul')
                    ->sortable(),
                TextColumn::make('Otkaz')
                    ->label('Otkaz')
                    ->sortable(),
                TextColumn::make('Yolda')
                    ->label('Yolda')
                    ->sortable(),
                TextColumn::make('Yetkazildi')
                    ->label('Yetkazildi')
                    ->sortable(),
                TextColumn::make('Sotildi')
                    ->label('Sotildi')
                    ->sortable(),
                TextColumn::make('QaytibKeldi')
                    ->label('Qaytib Keldi')
                    ->sortable(),
            ])
            ->filters([

            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
            ]);
//            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
//            ]);
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
            'index' => Pages\ListStatistics::route('/'),
//            'create' => Pages\CreateStatistics::route('/create'),
//            'edit' => Pages\EditStatistics::route('/{record}/edit'),
        ];
    }
}
