<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfitLossStatisticsResource\Pages;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('displayProductName')
                    ->label('Mahsulot nomi')
                    ->searchable(['displayProductName', 'article'])
                    ->toggleable(),
                Tables\Columns\TextInputColumn::make('target')
                    ->label("Harajat summa"),
                TextColumn::make('Lead')
                    ->label("Lead")
                    ->toggleable(),
                TextColumn::make('Qabul')
                    ->label("Qabul")
                    ->toggleable(),
                TextColumn::make('Sotildi')
                    ->label("Sotildi")
                    ->toggleable(),
                TextColumn::make('YoldaSotildi')
                    ->label("Yolda + Sotildi")
                    ->toggleable(),
                TextColumn::make('Profit')
                    ->label("Profit")
                    ->toggleable(),
                TextColumn::make('ProfitYolda')
                    ->label("Profit + Yolda")
                    ->toggleable()
            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListProfitLossStatistics::route('/'),
//            'create' => Pages\CreateProfitLossStatistics::route('/create'),
//            'edit' => Pages\EditProfitLossStatistics::route('/{record}/edit'),
        ];
    }
}
