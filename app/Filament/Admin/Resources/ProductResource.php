<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductResource\Pages\ListProducts;
use App\Models\Product;
use App\Models\Stream;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = "Mahsulotlar";
    protected static ?string $pluralModelLabel = "Mahsulotlar";
    protected static int $globalSearchResultsLimit = 20;
    protected static ?string $recordTitleAttribute = "name";
    public static function getGloballySearchableAttributes(): array
    {
        return ['article', 'name']; // TODO: Change the autogenerated stub
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->landing_title; // TODO: Change the autogenerated stub
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Name' => $record->name,
            'Article' => $record->article,
            'Title' => $record->landing_title
        ]; // TODO: Change the autogenerated stub
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'Ochiq')->count(); // TODO: Change the autogenerated stub
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name')
                    ->label('Mahsulot'),
                TextEntry::make('article')
                    ->label('Article'),
                TextEntry::make('buyPrice')
                    ->label('Sotuv narxi')
                    ->formatStateUsing(function ($state) {
                        // Format the state as a number with space as thousand separator
                        return number_format($state, 0, '.', ' ');
                    }),
                TextEntry::make('target')
                    ->label('Bonus')
                    ->formatStateUsing(function ($state) {
                        // Format the state as a number with space as thousand separator
                        return number_format($state, 0, '.', ' ');
                    }),
                TextEntry::make('ПВЗ')
                    ->label('ПВЗ'),
                TextEntry::make('for_two_free_delivery')
                    ->label('2->Yetkazish'),
                TextEntry::make('bonus')
                    ->label('2+1'),
                TextEntry::make('landing_title')
                    ->label('Title'),
                TextEntry::make('landing_description')
                    ->label('Description')
                    ->columnSpanFull(),
                TextEntry::make('landing_link')
                    ->label('LINK')
                    ->columnSpanFull()
                    ->url(fn ($record): string => $record->link ? url($record->link) : '#'),
                ImageEntry::make('landing_image')
                    ->label('Image')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('landing_image')
                        ->height('100%')
                        ->width('100%')
                        ->url(fn ($record) => $record->landing_link)
                        ->openUrlInNewTab()
                        ->getStateUsing(function ($record) {
                            // If the landing_image exists, return it.
                            if ($record->landing_image) {
                                return $record->landing_image;
                            }
                            $product = Product::where('name', $record->name)->first();
                            $generatedImageName = "{$product->id}.jpg";

                            // Assume the generated image might exist; you would normally check its existence in your storage.
                            // Here, let's return the generated name. In reality, you would check if the file exists.
                            return file_exists("images/{$generatedImageName}")
                                ? "images/{$generatedImageName}"
                                : "images/noimage.jpeg";
                        }),    // This gets the image URL
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->label("Name")
                            ->searchable(['name', 'article'])
                            ->formatStateUsing(function ($record) {
                                return '<span class="font-bold">Mahsulot: </span>' . e($record->name);
                            })
                            ->html(),
                        Tables\Columns\TextColumn::make('landing_title')
                            ->label("Title")
                            ->formatStateUsing(function (string $state) {
                                return '<span class="font-bold">Sarlavha: </span>' . e($state);
                            })
                            ->html(),
                        Tables\Columns\TextColumn::make('salePrice')
                            ->weight(FontWeight::Bold)
                            ->label("Sotuv narxi")
                            ->searchable()
                            ->formatStateUsing(function ($state) {
                                // Format the state as a number with space as thousand separator
                                $formattedState = number_format($state, 0, '.', ' ');

                                // Return the formatted state with the prefix in bold
                                return '<span class="font-bold" >Sotuv narxi: </span>'
                                    . '<span style="color: #fb923c; serif;">' . e($formattedState) . '</span>';
                            })
                            ->html(),
                        Tables\Columns\TextColumn::make('target')
                            ->weight(FontWeight::Bold)
                            ->label("Bonus")
                            ->searchable()
                            ->formatStateUsing(function ($state) {
                                // Format the state as a number with space as thousand separator
                                $formattedState = number_format($state, 0, '.', ' ');

                                // Return the formatted state with the prefix in bold
                                return '<span class="font-bold" >Bonus: </span>'
                                    . '<span style="color: #2B6CB0; serif;">' . e($formattedState) . '</span>';
                            })
                            ->html(),
                        Tables\Columns\TextColumn::make('bonus')
                            ->weight(FontWeight::Bold)
                            ->label("2+1")
                            ->formatStateUsing(function (string $state) {
                                return '<span class="font-bold">2+1: </span>' . e($state);
                            })
                            ->html(),
                    ]),
                ])->space(3),
                Tables\Columns\Layout\Panel::make([
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('landing_description')
                            ->color('gray'),
                    ]),
                ])->collapsible(),
            ])
            ->filters([
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->paginated([
                12,
                24,
                36,
                48,
                60,
                72,
                'all',
            ])
            ->hiddenFilterIndicators(true)
            ->actions([
                Tables\Actions\Action::make('landing_link')
                    ->label("Kreativ")
                    ->icon('heroicon-s-paper-airplane')
                    ->color('gray')
                    ->tooltip("Kreativ qo'shish")
                    ->button(),
                // Add your custom action here
                Tables\Actions\Action::make('create_stream')
                    ->label('Oqim olish') // The button label
                    ->icon('heroicon-s-paper-clip') // The button icon
                    ->color('primary') // Button color
                    ->action(function ($record, $livewire) {
                        // Define the action to open the modal form
                        $livewire->emit('openModal', 'Stream', [
                            'landing_id' => $record->landing_id, // Pass the product ID or any necessary data
                        ]);
                    })
                    ->modalHeading('Oqim olish') // The modal heading
                    ->tooltip("Oqim olish")
                    ->button() // The submit button label
                    ->modalSubmitActionLabel("Saqlash")
                    ->modalCancelActionLabel("Bekor qilish")
                    ->modalWidth('md')
                    ->form([
                        // Define the fields that will appear in the modal form
                        Forms\Components\TextInput::make('name')
                            ->label('Oqim Nomi')
                            ->placeholder('Oqimga nom qoying')
                            ->required()
                            ->minLength(4),
                        Forms\Components\TextInput::make('pixel_id')
                            ->label('Pixel Id')
                            ->placeholder('Pixel Id ni kiriting ...')
                            ->numeric() // Ensure that only numeric values are allowed
                            ->required() // Make the field required
                            ->minLength(16) // Optional: You can define a minimum length if needed
                            ->maxLength(16),
                        TextInput::make('salePrice')
                            ->label('Sotuv narxi')
                            ->default(fn ($record) => number_format($record->salePrice, 0, '.', ' ')) // Set default value from the record
                            ->readOnly(),
//                            ->formatStateUsing(fn ($state) => number_format($state, 0, '.', ' ')),

                        TextInput::make('target')
                            ->label('Bonus')
                            ->default(fn ($record) => number_format($record->target, 0, '.', ' ')) // Set default value from the record
                            ->readOnly(),
                    ])
                    ->action(function ($data, $record) {
                        // Define the action when the form is submitted
                        // Here you can process the form data
                        $stream = new Stream([
                            'name' => $data['name'],
                            'source' => auth()->id(),
                            'pixel_id' => $data['pixel_id'],
                            'landing_id' => $record->landing_id, // Associate with the current product
                        ]);
                        $stream->save();

                        Notification::make()
                            ->title('Muvaffaqiyatli olindi')
                            ->success()
                            ->body('Yangi oqim muvaffaqiyatli olindi.')
                            ->send();
                    }),

                Tables\Actions\Action::make('oqim_link')
                    ->tooltip('Oqim olingan link')
                    ->icon('heroicon-s-arrow-top-right-on-square')
                    ->iconButton()
                    ->url(function ($record): ?string {
                        // Assuming you want to fetch the latest stream for this landing_id
                        $stream = Stream::where('landing_id', $record->landing_id)
                            ->orderBy('createdAt', 'desc')
                            ->first();

                        if (! $stream || blank($stream->full_link)) {
                            return null;
                        }

                        return $stream->full_link;
                    }, shouldOpenInNewTab: true)
                    ->hidden(function ($record): bool {
                        $stream = Stream::where('landing_id', $record->landing_id)
                            ->orderBy('createdAt', 'desc')
                            ->first();

                        return blank($stream) || blank($stream->full_link);
                    }),
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
            'index' => ListProducts::route('/'),
        ];
    }
}
