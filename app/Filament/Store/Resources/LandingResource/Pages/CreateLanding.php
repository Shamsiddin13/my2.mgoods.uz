<?php

namespace App\Filament\Store\Resources\LandingResource\Pages;

use App\Filament\Store\Resources\CreativeResource;
use App\Filament\Store\Resources\LandingResource;
use App\Filament\Store\Resources\ProductResource\Pages\ListProducts;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateLanding extends CreateRecord
{
    protected static string $resource = LandingResource::class;

    protected static ?string $title = "Mahsulot qo'shish";

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title("Landing muvaffaqiyatli qo'shildi");
    }

    protected function getRedirectUrl(): string
    {
        return ListProducts::getUrl();
    }
}
