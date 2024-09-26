<?php

namespace App\Filament\Admin\Resources\ProfitLossStatisticsResource\Pages;

use App\Filament\Admin\Resources\ProfitLossStatisticsResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class UpdateTarget extends Page
{

    protected static string $resource = ProfitLossStatisticsResource::class;

    protected static string $view = 'filament.admin.resources.profit-loss-statistics-resource.pages.update-target';

    public $article;

    public $new_target;


    public function mount()
    {
        // Initialize any properties if needed
    }

    public function updateTarget()
    {
        $this->validate([
            'article' => 'required|exists:orders,article',
            'new_target' => 'required|numeric',
        ]);


        // Update target value for the selected article
        $result = Order::where('source', auth()->user()->source)->where('article', $this->article)->first()->update(['target' => $this->new_target]);
        if ($result) {
            Notification::make()
                ->title('Muvvaffaqiyatli yangilandi')
                ->body(str("Ushbu mahsulot uchun **harajat summa** <br>muvvaffaqiyatli yangilandi " .
                    'Artikul: ' . "**" . $this->article . "**" . '<br>'
                )->markdown())
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Not updated')
                ->body('The target value has not been updated for article: ' . $this->article)
                ->danger()
                ->send();
        }

        // Notify the user

        // Redirect or reset form
        $this->reset(['article', 'new_target']);
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('article')
                ->label('Artikul')
                ->placeholder('Artikulni Tanlang')
                ->options(
                    Order::orderBy('article', 'ASC')  // Add sorting here
                    ->where('source', auth()->user()->source)
                        ->get()
                        ->mapWithKeys(function ($order) {
                            return [$order->article => $order->displayProductName . ' | ' . $order->article];

                        })
                        ->toArray()
                )
                ->searchable()
                ->searchPrompt("mahsulot nomi yoki artikul bo'yicha qidiring")
                ->required()
                ->live(),

            TextInput::make('new_target')
                ->label('Harajat summa')
                ->placeholder('Harajat summani kiriting ..')
                ->numeric()
                ->required()
                ->live()
                ->extraAttributes(['class' => 'mb-4']),
        ];
    }

    private function Action(): Action
    {
        return Action::make(__('Update Target'))
            ->action('updateTarget')
            ->icon('heroicon-o-check')
            ->requiresConfirmation()
            ->color('primary');
    }
}
