<?php

namespace App\Filament\Store\Resources;

use App\Filament\Store\Resources\FinanceResource\Pages;
use App\Filament\Store\Resources\FinanceResource\Widgets\FinanceOverview;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FinanceResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = "Moliya";

    protected static ?string $pluralModelLabel = "Moliya";

    protected static ?int $navigationSort = 2;
    public static function getGloballySearchableAttributes(): array
    {
        return ['amount', 'date', 'description']; // TODO: Change the autogenerated stub
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('contractor_name', auth()->user()->store)->count(); // TODO: Change the autogenerated stub
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('contractor_name', auth()->user()->store);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->label("Transaction")
                    ->schema([
                        TextInput::make("card_number")
                            ->label("Karta raqam")
                            ->placeholder("Karta raqam kiriting")
                            ->minLength(16)
                            ->maxLength(16)
                            ->required(), // Make the input full width of the form

                        TextInput::make("amount")
                            ->label("Summa")
                            ->placeholder("Summa kiriting")
                            ->required(), // Make the input full width of the form
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_date')
                    ->label('Sana')
                    ->date()
                    ->sortable(),
                TextColumn::make('card_number')
                    ->label('Karta raqam')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Summa')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($state) => number_format($state, 0, '.', ' ')),
                TextColumn::make('status')
                    ->label('Holat')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Izoh')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Filter::make('transaction_date')
                    ->form([
                        DatePicker::make('created_from')->label("Tranzaksiya sana (dan)"),
                        DatePicker::make('created_until')->label("Tranzaksiya sana (gacha)"),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('transaction_date', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('transaction_date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        $columnLabel = 'Tranzaksiya sanasi';  // Default label

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
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }
    public static function getWidgets(): array
    {
        return [
            FinanceOverview::class,
        ]; // TODO: Change the autogenerated stub
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
            'index' => Pages\ListFinances::route('/'),
//            'create' => Pages\CreateFinance::route('/create'),
//            'edit' => Pages\EditFinance::route('/{record}/edit'),
        ];
    }
}
