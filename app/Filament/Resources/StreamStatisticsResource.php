<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StreamStatisticsResource\Pages;
use App\Models\Order;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StreamStatisticsResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Statistika';
    protected static ?string $pluralModelLabel = "Oqim";
    protected static ?string $navigationLabel = "Oqim";
    protected static ?string $navigationIcon = 'heroicon-o-paper-clip';
    public static function getEloquentQuery(): Builder
    {
        // Define the source (replace 'source_column' with the actual column name in your DB)
        $source = auth()->user()->source;

        return Order::selectRaw("
            MAX(displayProductName) as displayProductName,
            createdAt,
            SUM(Lead) as Lead,
            SUM(Qabul) as Qabul,
            SUM(Otkaz) as Otkaz,
            SUM(Yolda) as Yolda,
            SUM(Yetkazildi) as Yetkazildi,
            SUM(Sotildi) as Sotildi,
            SUM(QaytibKeldi) as QaytibKeldi
        ")
            ->from(function ($query) use ($source) {
                $query->selectRaw("
                DATE(createdAt) as createdAt, MAX(displayProductName) as displayProductName,
                COUNT(DISTINCT CASE WHEN status IN ('Новый', 'Принят', 'Недозвон', 'Отмена', 'В пути', 'Доставлен', 'Выполнен', 'Возврат', 'Подмены') THEN ID_number ELSE NULL END) AS Lead,
                COUNT(DISTINCT CASE WHEN status = 'Принят' THEN ID_number ELSE NULL END) AS Qabul,
                COUNT(DISTINCT CASE WHEN status = 'Отмена' THEN ID_number ELSE NULL END) AS Otkaz,
                COUNT(DISTINCT CASE WHEN status IN ('В пути', 'EMU') THEN ID_number ELSE NULL END) AS Yolda,
                COUNT(DISTINCT CASE WHEN status = 'Доставлен' THEN ID_number ELSE NULL END) AS Yetkazildi,
                COUNT(DISTINCT CASE WHEN status = 'Выполнен' THEN ID_number ELSE NULL END) AS Sotildi,
                COUNT(DISTINCT CASE WHEN status = 'Возврат' THEN ID_number ELSE NULL END) AS QaytibKeldi
            ")
                    ->from('orders')
                    ->where('source', '=', $source)
                    ->groupByRaw('DATE(createdAt)');
            }, 'daily_totals')
            ->groupBy('createdAt');
    }


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
                TextColumn::make('createdAt')
                    ->label('Sana')
                    ->sortable()
                    ->date()
                    ->toggleable(),
                TextColumn::make('displayProductName')
                    ->label('Mahsulot')
                    ->sortable()
                    ->searchable(['displayProductName', 'article'])
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                TextColumn::make('Lead')
                    ->label('Lead')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('Qabul')
                    ->label('Qabul')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('Otkaz')
                    ->label('Otkaz')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('Yolda')
                    ->label('Yolda')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('Yetkazildi')
                    ->label('Yetkazildi')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('Sotildi')
                    ->label('Sotildi')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('QaytibKeldi')
                    ->label('Qaytib Keldi')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('createdAt', 'DESC')
            ->paginated([
                10,
                15,
                25,
                40,
                50,
                'all',
            ])
            ->filters([
                Filter::make('createdAt')
                    ->form([
                        DatePicker::make('created_from')->label("Buyurtma sanasi (dan)"),
                        DatePicker::make('created_until')->label("Buyurtma sanasi (gacha)"),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('createdAt', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('createdAt', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        $columnLabel = 'Buyurtma sanasi';

                        if ($data['created_from'] ?? null) {
                            $indicators[] = Indicator::make($columnLabel . ' (dan) ' . Carbon::parse($data['created_from'])->toFormattedDateString())
                                ->removeField('created_from');
                        }

                        if ($data['created_until'] ?? null) {
                            $indicators[] = Indicator::make($columnLabel . ' (gacha) ' . Carbon::parse($data['created_until'])->toFormattedDateString())
                                ->removeField('created_until');
                        }

                        return $indicators;
                    }),
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
            'index' => Pages\ListStreamStatistics::route('/'),
//            'create' => Pages\CreateStreamStatistics::route('/create'),
//            'edit' => Pages\EditStreamStatistics::route('/{record}/edit'),
        ];
    }
}
