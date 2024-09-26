<?php

namespace App\Filament\Storekeeper\Resources\WarehouseResource\Pages;

use App\Filament\Storekeeper\Resources\WarehouseDetailsResource\Pages\ListWarehouseDetails;
use App\Filament\Storekeeper\Resources\WarehouseResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateWarehouse extends CreateRecord
{
    protected static string $resource = WarehouseResource::class;

    protected static ?string $navigationLabel = 'Omborga Kirim & Chiqim';

    protected function getRedirectUrl(): string
    {
        return ListWarehouseDetails::getUrl();
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title("Muvaffaqiyatli qo'shildi")
            ->body(str("Omborga **Kirim | Chiqim** muvaffaqiyatli qo'shildi")->markdown());
    }
}
