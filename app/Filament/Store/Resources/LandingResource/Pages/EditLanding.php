<?php

namespace App\Filament\Store\Resources\LandingResource\Pages;

use App\Filament\Store\Resources\LandingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLanding extends EditRecord
{
    protected static string $resource = LandingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
