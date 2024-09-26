<?php

namespace App\Filament\Store\Resources;

use App\Filament\Store\Resources\LandingResource\Pages;
use App\Models\Landing;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class LandingResource extends Resource
{
    protected static ?string $model = Landing::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make("Mahsulot")
                    ->collapsible()
                    ->schema([
                        Hidden::make('store')
                            ->dehydrated()
                            ->default(auth()->user()->store),
                        Select::make('article')
                            ->label('Artikul')
                            ->preload()
                            ->options(function ($get) {
                                $store = $get('store');

                                if ($store) {
                                    return Product::where('store', $store)->pluck('article', 'article')->filter()->toArray();
                                }
                                return [];
                            })
                            ->required()
                            ->validationMessages([
                                'required' => "Artikul maydonini kiritish talab etiladi"
                            ])
                            ->searchPrompt("artikul nomi bo'yicha qidiring ..")
                            ->placeholder('Artikul tanlang')
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                if (!empty($state)) {
                                    $selected_product = Product::where('article', $get('article') ?? null)->first();
                                    $set('product_name', $selected_product->name ?? null);
                                } else {
                                    $set('product_name', null);
                                }
                            }),
                        TextInput::make('product_name')
                            ->label('Tanlangan Mahsulot')
                            ->disabled()
                            ->readonly(),
                        Textarea::make('title')
                            ->label('Sarlavha')
                            ->required()
                            ->validationMessages([
                                'required' => "Sarlavha maydonini kiritish talab etiladi"
                            ])
                            ->placeholder('Sarlavhani kiriting ..'),
                        Textarea::make('subtitle')
                            ->label('Subtitr/Taglavha')
                            ->required()
                            ->validationMessages([
                                'required' => "Subtitr maydonini kiritish talab etiladi"
                            ])
                            ->placeholder('Subtitrni kiriting ..'),
                        Textarea::make('description')
                            ->label('Tavsif')
                            ->required()
                            ->validationMessages([
                                'required' => "Tavsif maydonini kiritish talab etiladi"
                            ])
                            ->placeholder('Tavsifini kiriting ..')
                            ->columnSpanFull(),
                    ])->columnSpan(3)->columns(2),
                Group::make()->schema([
                    Section::make('Tekstlar')
                        ->collapsible()
                        ->schema([
                            Textarea::make('text1')
                                ->label('1 - Tekst')
                                ->validationMessages([
                                    'required' => "1 - Tekst maydonini kiritish talab etiladi"
                                ])
                                ->required(),
                            Textarea::make('text2')
                                ->label('2 - Tekst')
                                ->validationMessages([
                                    'required' => "2 - Tekst maydonini kiritish talab etiladi"
                                ])
                                ->required(),
                            Textarea::make('text3')->label('3 - Tekst'),
                        ])->columnSpan(1)
                ]),
                Section::make('Rasmlar')
                    ->collapsible()
                    ->schema([
                        FileUpload::make('img1')
                            ->label('Asosiy Rasm')
                            ->disk('public')->directory('images')
                            ->validationMessages([
                                'required' => "Asosiy rasmni yuklash talab etiladi"
                            ])
                            ->required(),
                        FileUpload::make('img2')
                            ->label('1 - Rasm')
                            ->disk('public')->directory('images')
                            ->validationMessages([
                                'required' => "1 - Rasmni yuklash talab etiladi"
                            ])
                            ->required(),
                        FileUpload::make('img3')
                            ->label('2 - Rasm')
                            ->disk('public')->directory('images')
                            ->validationMessages([
                                'required' => "2 - Rasmni yuklash talab etiladi"
                            ])
                            ->required(),
                        FileUpload::make('img4')
                            ->label('3 - Rasm')
                            ->disk('public')->directory('images')
                            ->validationMessages([
                                'required' => "3 - Rasmni yuklash talab etiladi"
                            ])
                            ->required(),
                    ])->columnSpan([
                        'md' => 2,
                        'lg' => 0,
                        'xl' => 4,
                    ])->columns([
                        'md' => 2,
                        'lg' => 0,
                        'xl' => 4,
                    ]),
            ])->columns([
                'default' => 1,
                'md' => 2,
                'lg' => 3,
                'xl' => 4,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListLandings::route('/'),
            'create' => Pages\CreateLanding::route('/create'),
        ];
    }
}
