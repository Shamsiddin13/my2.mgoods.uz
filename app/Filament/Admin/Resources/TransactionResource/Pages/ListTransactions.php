<?php

namespace App\Filament\Admin\Resources\TransactionResource\Pages;

use App\Filament\Admin\Resources\TransactionResource;
use App\Filament\Storekeeper\Resources\WarehouseResource;
use App\Models\RequestFin;
use App\Models\User;
use App\Notifications\Target;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;
    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make('createRequestFin')
                ->label("Pul chiqarish")
                ->icon('heroicon-o-currency-dollar')
                ->color('warning')
                ->modalHeading('Pul Chiqarish')
                ->form([
                    Section::make("Pul Chiqarish")
                        ->schema([
                            TextInput::make('account')
                                ->label('Karta raqam')
                                ->placeholder('Yaroqli karta raqamini kiriting ..')
                                ->maxLength(16)
                                ->minLength(16)
                                ->integer()
                                ->validationMessages([
                                    'required' => "Karta raqam maydonini kiritish talab etiladi.",
                                ])
                                ->rules(['required', 'digits:16', 'min:16', 'max:16'])
                                ->tel()
                                ->required(),
                            TextInput::make('amount')
                                ->label('Summa')
                                ->placeholder('Summani kiriting ..')
                                ->validationMessages([
                                    'required' => "Summa maydonini kiritish talab etiladi."
                                ])
                                ->minValue(0.01)
                                ->maxValue(10000000000.00)
                                ->numeric()
                                ->step(0.01)
                                ->required()
                        ])->columnSpan(2)
                ])
                ->action(function (array $data) {
                    $newRequestFin = RequestFin::create([
                        'user_id' => auth()->user()->id,
                        'user_type' => auth()->user()->type,
                        'account' => $data['account'],
                        'amount' => $data['amount'],
                        'status' => 'new',
                    ]);

                    $user = User::where('type', 'superadmin')->first();
                    // Notify the user
                    $user->notify(new Target($newRequestFin, "new"));

                    Notification::make()
                        ->title("Pul chiqarish so'rovi muvaffaqiyatli yuborildi !")
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return TransactionResource::getWidgets();
    }

    public function setPage($page, $pageName = 'page'): void
    {
        parent::setPage($page, $pageName);

        $this->dispatch('scroll-to-top');
    }
}
