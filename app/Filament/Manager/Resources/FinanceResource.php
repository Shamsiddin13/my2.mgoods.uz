<?php

namespace App\Filament\Manager\Resources;

use App\Filament\Manager\Resources\FinanceResource\Pages;
use App\Filament\Manager\Resources\FinanceResource\Widgets\FinanceOverview;
use App\Models\Transaction;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class FinanceResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationLabel = "Moliya";

    protected static ?string $pluralModelLabel = "Moliya";

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();
        return static::getModel()::where('contractor_name', $user->manager)->count(); // TODO: Change the autogenerated stub
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('contractor_name', auth()->user()->manager);
    }
    public static function getGloballySearchableAttributes(): array
    {
        return ['amount', 'date', 'description']; // TODO: Change the autogenerated stub
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
