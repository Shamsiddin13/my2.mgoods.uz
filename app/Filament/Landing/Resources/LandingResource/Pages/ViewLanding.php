<?php

namespace App\Filament\Landing\Resources\LandingResource\Pages;

use App\Filament\Landing\Resources\LandingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLanding extends ViewRecord
{
    protected static string $resource = LandingResource::class;

    protected static ?string $title = "Landingni Ko'rish";
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('Tahrirlash'),
        ];
    }
}
