<?php

namespace App\Filament\Store\Resources;

use App\Filament\Store\Resources\CreativeResource\Pages;
use App\Models\Creative;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class CreativeResource extends Resource
{
    protected static ?string $model = Creative::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';

    protected static ?string $navigationLabel = "Kreativlar";

    protected static ?string $pluralModelLabel = "Kreativlar";

    protected static ?int $navigationSort = 3;
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make("Kreativ")
                    ->collapsible()
                    ->schema([
                        Forms\Components\Hidden::make('user_id')
                            ->dehydrated()
                            ->default(auth()->user()->id),
                        Forms\Components\Select::make('article')
                            ->label('Artikul')
                            ->placeholder('Artikulni tanlang ..')
                            ->preload()
                            ->searchable()
                            ->searchPrompt("artikul bo'yicha qidiring ..")
                            ->required()
                            ->validationMessages([
                                'required' => "Artikul kiritish talab etiladi"
                            ])
                            ->options(fn(callable $get) => Product::where('store', auth()->user()->store)->get()
                                ->pluck('article', 'article'))
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
                            ->label('Tanlangan mahsulot')
                            ->readonly()
                            ->disabled(),
                        TextArea::make('title')
                            ->label('Sarlavha')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Sarlavhani kiriting ..')
                            ->validationMessages([
                                'required' => "Sarlavhani kiritish talab etiladi"
                            ]),
                        Textarea::make('description')
                            ->label('Tavsif')
                            ->required()
                            ->placeholder('Tavsifini kiriting ..')
                            ->validationMessages([
                                'required' => "Tavsifini kiritish talab etiladi"
                            ]),
                        FileUpload::make('video')
                            ->label('Video')
                            ->disk('public')
                            ->directory('creative_videos')
                            ->required()
                            ->acceptedFileTypes(['video/mp4', 'video/avi', 'video/mov', 'video/webm'])
                            ->maxSize(102400) // Maximum size in KB (e.g., 100MB)
                            ->preserveFilenames() // Optional: Preserve original filenames
                            ->multiple(false) // Set to true if multiple videos can be uploaded
                            ->maxFiles(1) // Maximum number of files allowed
                            ->visibility('public') // Optional: Set file visibility
                            ->validationMessages([
                                'required' => "Video yuklanishi talab etiladi",
                                'file.max' => "Video hajmi 100MB dan oshmasligi kerak.",
                                'file.accepted' => "Faqat quyidagi formatdagi videolar qabul qilinadi: mp4, avi, mov, webm."
                            ])
                            ->columnSpanFull(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('article')
                    ->label('Artikul')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Sarlavha')
                    ->sortable()
                    ->limit(10)
                    ->tooltip(fn($record) => $record->title)
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Tavsif')
                    ->limit(10)
                    ->toggleable()
                    ->tooltip(fn($record) => $record->description),
                TextColumn::make('created_at')
                    ->label('Yaratilgan Vaqt')
                    ->date()
                    ->toggleable()
                    ->sortable()
                    ->tooltip(fn($record) => Carbon::parse($record->createdAt)->format('Y-m-d H:i:s')),
                TextColumn::make('updated_at')
                    ->label('Yangilangan Vaqt')
                    ->date()
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable()
                    ->tooltip(fn($record) => Carbon::parse($record->updatedAt)->format('Y-m-d H:i:s')),
            ])
            ->defaultSort('created_at', 'DESC')
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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()->label("Batafsil ko'rish"),
                    Tables\Actions\EditAction::make()->label("Tahrirlash"),
                    Tables\Actions\DeleteAction::make()->label("O'chirish"),
                ])
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
            'index' => Pages\ListCreatives::route('/'),
            'create' => Pages\CreateCreative::route('/create'),
        ];
    }
}
