<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StreamStatisticsResource\Pages\ListStreamStatistics;
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
use Illuminate\Support\Facades\DB;

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
        $user = auth()->user();
        if (is_null($user)) {
            throw new \Exception("No authenticated user found.");
        }

        $source = $user->source;

        return Order::query()
            ->select([
                'stream.stream_name',
                'orders.displayProductName',
                DB::raw("COUNT(DISTINCT CASE WHEN orders.status IN (
                'new', 'updated', 'recall', 'call_late', 'cancel', 'accept',
                'send', 'delivered', 'returned', 'sold'
            ) THEN orders.ID_number ELSE NULL END) AS `Lead`"),
                DB::raw("COUNT(DISTINCT CASE WHEN orders.status = 'accept' THEN orders.ID_number ELSE NULL END) AS `Qabul`"),
                DB::raw("COUNT(DISTINCT CASE WHEN orders.status = 'cancel' THEN orders.ID_number ELSE NULL END) AS `Otkaz`"),
                DB::raw("COUNT(DISTINCT CASE WHEN orders.status = 'send' THEN orders.ID_number ELSE NULL END) AS `Yolda`"),
                DB::raw("COUNT(DISTINCT CASE WHEN orders.status = 'delivered' THEN orders.ID_number ELSE NULL END) AS `Yetkazildi`"),
                DB::raw("COUNT(DISTINCT CASE WHEN orders.status = 'sold' THEN orders.ID_number ELSE NULL END) AS `Sotildi`"),
                DB::raw("COUNT(DISTINCT CASE WHEN orders.status = 'returned' THEN orders.ID_number ELSE NULL END) AS `QaytibKeldi`"),
            ])
            ->join('stream', 'orders.link', '=', 'stream.link')
            ->where('orders.source', $source)
            ->groupBy('stream.stream_name', 'orders.displayProductName')
            ->orderBy('stream.stream_name');
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
                TextColumn::make('stream_name')
                    ->label('Oqim nomi')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('displayProductName')
                    ->label('Mahsulot')
                    ->sortable()
                    ->searchable(['displayProductName'])
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                TextColumn::make('Lead')
                    ->label('Lead')
                    ->badge()
                    ->color(function ($record) {
                        return $record->Lead > 0 ? 'warning' : ($record->Lead <= 0 ? 'gray' : null);
                    })
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('Qabul')
                    ->label('Qabul')
                    ->badge()
                    ->color(function ($record) {
                        return $record->Qabul > 0 ? 'info' : ($record->Qabul <= 0 ? 'gray' : null);
                    })
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('Otkaz')
                    ->label('Otkaz')
                    ->badge()
                    ->color(function ($record) {
                        return $record->Otkaz > 0 ? 'danger' : ($record->Otkaz <= 0 ? 'gray' : null);
                    })
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('Yolda')
                    ->label('Yolda')
                    ->badge()
                    ->icon(function ($record) {
                        return $record->Yolda > 0 ? 'heroicon-m-truck' : ($record->Yolda <= 0 ? null : null);
                    })
                    ->color(function ($record) {
                        return $record->Yolda > 0 ? 'info' : ($record->Yolda <= 0 ? 'gray' : null);
                    })
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('Yetkazildi')
                    ->label('Yetkazildi')
                    ->badge()
                    ->icon(function ($record) {
                        return $record->Yetkazildi > 0 ? 'heroicon-m-check-circle' : ($record->Yetkazildi <= 0 ? null : null);
                    })
                    ->color(function ($record) {
                        return $record->Yetkazildi > 0 ? 'info' : ($record->Yetkazildi <= 0 ? 'gray' : null);
                    })
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('Sotildi')
                    ->label('Sotildi')
                    ->badge()
                    ->icon(function ($record) {
                        return $record->Sotildi > 0 ? 'heroicon-m-check-badge' : ($record->Sotildi <= 0 ? null : null);
                    })
                    ->color(function ($record) {
                        return $record->Sotildi > 0 ? 'success' : ($record->Sotildi <= 0 ? 'gray' : null);
                    })
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('QaytibKeldi')
                    ->label('Qaytib Keldi')
                    ->badge()
                    ->color(function ($record) {
                        return $record->QaytibKeldi > 0 ? 'danger' : ($record->QaytibKeldi <= 0 ? 'gray' : null);
                    })
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('stream_name')
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
            'index' => ListStreamStatistics::route('/')
        ];
    }
}
