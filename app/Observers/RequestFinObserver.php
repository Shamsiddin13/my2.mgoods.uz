<?php

namespace App\Observers;

use App\Models\RequestFin;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;

class RequestFinObserver
{
    /**
     * Handle the RequestFin "created" event.
     */
    public function created(RequestFin $requestFin): void
    {
        $recipient = auth()->user();

        Notification::make()
            ->title("Pul chiqarish so'rovi muvaffaqiyatli yuborildi !")
            ->body(str(
                "**Holati:** " . 'Yangi' . "<br>" .
                "**Karta raqam:** " . $this->formatAccountNumber($requestFin->account) . "<br>" .
                "**Summa:** " . $this->formatAmount($requestFin->amount) . " UZS" . "<br>" .
                "**Sana-Vaqti:** " . $this->formatDate($requestFin->created_at))
            ->markdown())
            ->icon('heroicon-o-currency-dollar')
            ->iconColor('warning')
            ->success()
            ->actions([
                Action::make('markAsRead')
                    ->button()
                    ->label("O‘qilgan")
                    ->tooltip("Habarni O'qilgan deb belgilash")
                    ->markAsRead(),
            ])
            ->sendToDatabase($recipient);

    }

    /**
     * Handle the RequestFin "updated" event.
     */
    public function updated(RequestFin $requestFin): void
    {
        $recipient = auth()->user();

        Notification::make()
            ->title("The record(s) status updated successfully")
            ->body(str("**ID:** " . $requestFin->id . "<br>" .
                "**Status:** " . $requestFin->status . "<br>" .
                "**Updated At:** " . $this->formatDate($requestFin->updated_at) . "<br>" .
                "**Amount:** " . $this->formatAmount($requestFin->amount) . " UZS")->markdown())
            ->success()
            ->seconds(3)
            ->send();
    }

    /**
     * Handle the RequestFin "deleted" event.
     */
    public function deleted(RequestFin $requestFin): void
    {
        //
    }

    /**
     * Handle the RequestFin "restored" event.
     */
    public function restored(RequestFin $requestFin): void
    {
        //
    }

    /**
     * Handle the RequestFin "force deleted" event.
     */
    public function forceDeleted(RequestFin $requestFin): void
    {
        //
    }

    public function formatAccountNumber($accountNumber) {
        return implode(' ', str_split((string)$accountNumber, 4));
    }

    public function formatAmount($amount) {
        return number_format($amount, 0, '.', ' ');
    }

    public function formatDate($datetime) {
        return Carbon::parse($datetime)->format('d-m-Y H:i:s');
    }

}
