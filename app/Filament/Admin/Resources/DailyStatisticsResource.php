<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StatisticsResource\Pages\ListStatistics;
use App\Models\Order;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DailyStatisticsResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?int $navigationSort = 0;
    protected static ?string $navigationGroup = 'Statistika';
    protected static ?string $pluralModelLabel = "Kunlik";
    protected static ?string $navigationLabel = "Kunlik";
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }
    public function getTableRecordKey(Model $record): string
    {
        return uniqid();
    }

    public static function getEloquentQuery(): Builder
    {
        $source = auth()->user()->source;

        return Order::query()
            ->fromSub(function ($query) use ($source) {
                $query->from('orders')
                    ->selectRaw("
                    DATE(createdAt) AS createdAt,
                    displayProductName,
                    COUNT(DISTINCT CASE WHEN status IN (
                        'new', 'updated', 'recall', 'call_late', 'cancel', 'accept',
                        'send', 'delivered', 'returned', 'sold'
                    ) THEN ID_number ELSE NULL END) AS `Lead`,
                    COUNT(DISTINCT CASE WHEN status = 'accept' THEN ID_number ELSE NULL END) AS `Qabul`,
                    COUNT(DISTINCT CASE WHEN status = 'cancel' THEN ID_number ELSE NULL END) AS `Otkaz`,
                    COUNT(DISTINCT CASE WHEN status = 'send' THEN ID_number ELSE NULL END) AS `Yolda`,
                    COUNT(DISTINCT CASE WHEN status = 'delivered' THEN ID_number ELSE NULL END) AS `Yetkazildi`,
                    COUNT(DISTINCT CASE WHEN status = 'sold' THEN ID_number ELSE NULL END) AS `Sotildi`,
                    COUNT(DISTINCT CASE WHEN status = 'returned' THEN ID_number ELSE NULL END) AS `QaytibKeldi`
                ")
                    ->where('source', $source)
                    ->groupByRaw('DATE(createdAt), displayProductName');
            }, 'daily_totals')
            ->select(
                'createdAt',
                'displayProductName',
                DB::raw('SUM(`Lead`) as `Lead`'),
                DB::raw('SUM(`Qabul`) as `Qabul`'),
                DB::raw('SUM(`Otkaz`) as `Otkaz`'),
                DB::raw('SUM(`Yolda`) as `Yolda`'),
                DB::raw('SUM(`Yetkazildi`) as `Yetkazildi`'),
                DB::raw('SUM(`Sotildi`) as `Sotildi`'),
                DB::raw('SUM(`QaytibKeldi`) as `QaytibKeldi`')
            )
            ->groupBy('createdAt', 'displayProductName');
    }




    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('createdAt')
                    ->label('Sana')
                    ->sortable()
                    ->date(),
                TextColumn::make('displayProductName')
                    ->label('Mahsulot')
                    ->sortable()
                    ->searchable(['displayProductName'])
                    ->toggleable(),
                TextColumn::make('Lead')
                    ->label('Lead')
                    ->sortable()
                    ->badge()
                    ->color(function ($record) {
                        return $record->Lead > 0 ? 'warning' : ($record->Lead <= 0 ? 'gray' : null);
                    })
                    ->toggleable(),
                TextColumn::make('Qabul')
                    ->label('Qabul')
                    ->sortable()
                    ->badge()
                    ->color(function ($record) {
                        return $record->Qabul > 0 ? 'info' : ($record->Qabul <= 0 ? 'gray' : null);
                    })
                    ->toggleable(),
                TextColumn::make('Otkaz')
                    ->label('Otkaz')
                    ->sortable()
                    ->badge()
                    ->color(function ($record) {
                        return $record->Otkaz > 0 ? 'danger' : ($record->Otkaz <= 0 ? 'gray' : null);
                    })
                    ->toggleable(),
                TextColumn::make('Yolda')
                    ->label('Yolda')
                    ->sortable()
                    ->badge()
                    ->icon(function ($record) {
                        return $record->Yolda > 0 ? 'heroicon-m-truck' : ($record->Yolda <= 0 ? null : null);
                    })
                    ->color(function ($record) {
                        return $record->Yolda > 0 ? 'info' : ($record->Yolda <= 0 ? 'gray' : null);
                    })
                    ->toggleable(),
                TextColumn::make('Yetkazildi')
                    ->label('Yetkazildi')
                    ->sortable()
                    ->badge()
                    ->icon(function ($record) {
                        return $record->Yetkazildi > 0 ? 'heroicon-m-check-circle' : ($record->Yetkazildi <= 0 ? null : null);
                    })
                    ->color(function ($record) {
                        return $record->Yetkazildi > 0 ? 'info' : ($record->Yetkazildi <= 0 ? 'gray' : null);
                    })
                    ->toggleable(),
                TextColumn::make('Sotildi')
                    ->label('Sotildi')
                    ->sortable()
                    ->badge()
                    ->icon(function ($record) {
                        return $record->Sotildi > 0 ? 'heroicon-m-check-badge' : ($record->Sotildi <= 0 ? null : null);
                    })
                    ->color(function ($record) {
                        return $record->Sotildi > 0 ? 'success' : ($record->Sotildi <= 0 ? 'gray' : null);
                    })
                    ->toggleable(),
                TextColumn::make('QaytibKeldi')
                    ->label('Qaytib Keldi')
                    ->sortable()
                    ->badge()
                    ->color(function ($record) {
                        return $record->QaytibKeldi > 0 ? 'danger' : ($record->QaytibKeldi <= 0 ? 'gray' : null);
                    })
                    ->toggleable(),
            ])
            ->defaultSort('createdAt', 'DESC')
            ->paginated([
                10,
                15,
                25,
                40,
                50,
                100,
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
            'index' => ListStatistics::route('/'),
        ];
    }
}
