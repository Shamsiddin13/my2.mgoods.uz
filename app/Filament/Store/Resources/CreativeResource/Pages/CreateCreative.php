<?php

namespace App\Filament\Store\Resources\CreativeResource\Pages;

use App\Filament\Store\Resources\CreativeResource;
use App\Filament\Store\Resources\ProductResource\Pages\ListProducts;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCreative extends CreateRecord
{
    protected static string $resource = CreativeResource::class;

    protected static ?string $title = "Kreativ qo'shish";

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
                ->success()
                ->title("Kreativ muvaffaqiyatli qo'shildi");
    }
    protected function getRedirectUrl(): string
    {
        return ListProducts::getUrl();
    }
}
