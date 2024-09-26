<?php

namespace App\Filament\Landing\Resources\LandingResource\Pages;

use App\Filament\Landing\Resources\LandingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLanding extends EditRecord
{
    protected static string $resource = LandingResource::class;

    protected static ?string $title = "Landingni Tahrirlash";
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label("O'chirish"),
        ];
    }
}
