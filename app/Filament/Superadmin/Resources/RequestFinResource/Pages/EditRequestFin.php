<?php

namespace App\Filament\Superadmin\Resources\RequestFinResource\Pages;

use App\Filament\Superadmin\Resources\RequestFinResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditRequestFin extends EditRecord
{
    protected static string $resource = RequestFinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
