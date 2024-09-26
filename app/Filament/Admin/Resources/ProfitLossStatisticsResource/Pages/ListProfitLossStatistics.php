<?php

namespace App\Filament\Admin\Resources\ProfitLossStatisticsResource\Pages;

use App\Filament\Admin\Resources\ProfitLossStatisticsResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\View\Components\Modal;
use Filament\View\LegacyComponents\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class ListProfitLossStatistics extends ListRecords
{
    protected static string $resource = ProfitLossStatisticsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('updateTarget')
                ->label('Harajat summa')
                ->icon('heroicon-o-pencil')
                ->color('primary')
                ->modalHeading('Harajat Summani Yangilash')
                ->modalWidth('md')
                ->form([
                    Select::make('article')
                        ->label('Mahsulot')
                        ->placeholder('Mahsulotni Tanlang')
                        ->options(function () {
                            return Order::orderBy('article', 'ASC')
                                ->where('source', auth()->user()->source)
                                ->get()
                                ->pluck('displayProductName', 'article')
                                ->toArray();
                        })
//                        ->getSearchResultsUsing(function (string $search) {
//                            return Order::where('source', auth()->user()->source)
//                                ->where('article', 'like', "%{$search}%")
//                                ->orWhere('displayProductName', 'like', "%{$search}%")
//                                ->limit(50)
//                                ->pluck('displayProductName', 'article')
//                                ->toArray();
//                        })
                        ->searchable()
                        ->preload()
                        ->searchPrompt("mahsulot nomi bo'yicha qidiring")
                        ->required(),

                    TextInput::make('new_target')
                        ->label('Harajat summa')
                        ->placeholder('Harajat summani kiriting ..')
                        ->numeric()
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $validatedData = Validator::make($data, [
                        'article' => 'required|exists:orders,article',
                        'new_target' => 'required|numeric',
                    ])->validate();

                    $order = Order::where('source', auth()->user()->source)
                        ->where('article', $validatedData['article'])
                        ->first();

                    if ($order) {
                        $order->update(['target' => $validatedData['new_target']]);

                        Notification::make()
                            ->title('Muvaffaqiyatli yangilandi')
                            ->body(str(
                                "Ushbu mahsulot uchun **harajat summa** <br>muvaffaqiyatli yangilandi.<br>" .
                                "Artikul: **{$validatedData['article']}**"
                            )->markdown())
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Yangilash amalga oshmadi')
                            ->body('Kiritilgan artikul uchun yozuv topilmadi: ' . $validatedData['article'])
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    public function getTableRecordKey(Model $record): string
    {
        return uniqid();
    }

    public function setPage($page, $pageName = 'page'): void
    {
        parent::setPage($page, $pageName);

        $this->dispatch('scroll-to-top');
    }
}
