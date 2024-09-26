<?php

namespace App\Filament\Storekeeper\Resources;

use App\Filament\Storekeeper\Resources\WarehouseResource\Pages;
use App\Models\Product;
use App\Models\Warehouse;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WarehouseResource extends Resource
{
    protected static ?string $model = Warehouse::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $modelLabel = 'Ombor';

    protected static ?string $pluralModelLabel = 'Ombor';

    protected static ?int $navigationSort = 2;
    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count(); // TODO: Change the autogenerated stub
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('details'); // TODO: Change the autogenerated stub
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Hidden::make('user_id')
                        ->dehydrated()
                        ->default(auth()->user()->id),
                    Section::make()
                        ->collapsible()
                        ->schema([
                    Repeater::make('details')
                        ->label('Omborga Kirim & Chiqim')
                        ->collapsible()
                        ->relationship()
                        ->schema([
                            Select::make('store')
                                ->label("Do'kon")
                                ->required()
                                ->columnSpan([
                                    'lg' => 3  // Span 3 of 12 columns on large screens
                                ])
                                ->validationMessages([
                                    'required' => "Do'kon maydonini kiritish shart"
                                ])
                                ->searchable()
                                ->searchPrompt("do'kon nomi bo'yicha qidiring ..")
                                ->placeholder("Do'konni tanglang ..")
                                ->options(Product::groupBy('store')->whereNotNull('store')->pluck('store', 'store'))
                                ->reactive(),
                            Select::make('article')
                                ->label('Mahsulot nomi')
                                ->required()
                                ->validationMessages([
                                    'required' => "Mahsulot nomi maydonini kiritish shart"
                                ])
                                ->searchable(['article', 'name'])
                                ->searchPrompt("artikul yoki mahsulot nomi bo'yicha qidiring ..")
                                ->placeholder('Mahsulotni tanglang ..')
                                ->columnSpan([
                                    'lg' => 7   // Span 3 of 12 columns on large screens
                                ])
                                ->dehydrated()
                                ->disabled(fn(callable $get) => empty($get('store')))
                                ->options(fn(callable $get) => !empty($get('store')) ? Product::where('store', $get('store'))->get()
                                    ->pluck('name', 'article')
                                    ->map(fn($article, $name) => "$name | $article") : [])
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                    if (!empty($state)) {
                                        $selected_product = Product::where('article', explode(' | ', $state)[0] ?? null)->first();
                                        $set('product_article', $selected_product->article ?? null);
                                        $set('product_name', $selected_product->name ?? null);
                                    } else {
                                        $set('product_article', null);
                                    }
                                }),
                            TextInput::make('product_article')
                                ->label('Artikul')
                                ->readonly()
                                ->disabled()
                                ->dehydrated()
                                ->columnSpan(['lg' => 2]),
                            Select::make('type')
                                ->label('Type')
                                ->placeholder('Kirim yoki Chiqim tanlang ..')
                                ->required()
                                ->validationMessages([
                                    'required' => "Ombor operatsiya turini kiritish shart"
                                ])
                                ->columnSpan([
                                    'lg' => 3   // Span 3 of 12 columns on large screens
                                ])
                                ->options([
                                    'income' => 'Kirim',
                                    'outcome' => 'Chiqim',
                                ]),
                            TextInput::make('amount')
                                ->label('Miqdor')
                                ->columnSpan(['lg' => 2])
                                ->required()
                                ->validationMessages([
                                    'required' => "Miqdor maydonini kiritish shart"
                                ])
                                ->numeric()
                                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                    if (!empty($state)) {
                                        $selected_product = Product::where('article',$get('article'))->first();
                                        $set('total_price', $get('amount') * $selected_product->buyPrice ?? 0.00);
                                    } else {
                                        $set('total_price', 0.00);
                                    }
                                }),
                            TextArea::make('comment')
                                ->label('Izoh')
                                ->columnSpan(['lg' => 3]),
                            Hidden::make('total_price')
                                ->dehydrated(),
                            Hidden::make('product_name')
                                ->dehydrated(),
                            Hidden::make('user_id')
                                ->dehydrated()
                                ->default(auth()->user()->id)
                        ])->addActionLabel("Qator qo'shish")
                        ->deletable()
                        ->orderColumn('created_at')
                        ->minItems(1)
                        ->columnSpanFull()
                        ->defaultItems(1)->columns(10),
                        ])
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Sana')
                    ->date()
                    ->sortable()
                    ->tooltip(function ($record) {
                        return $record->created_at->format('d-m-Y H:i:s');
                    })
                    ->toggleable(),
                TextColumn::make("store")
                    ->label("Do'kon")
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
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
                ActionGroup::make([
                    Tables\Actions\EditAction::make()->label('Tahrirish'),
                    Tables\Actions\DeleteAction::make()->label("O'chirish"),
                    Tables\Actions\ViewAction::make()->label("Batafsil ko'rish"),
                ]),
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
            'create' => Pages\CreateWarehouse::route('/create'),
        ];
    }
}
