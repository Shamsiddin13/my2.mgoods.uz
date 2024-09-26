<?php

namespace App\Filament\Landing\Resources\LandingResource\Pages;

use App\Filament\Landing\Resources\LandingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLanding extends CreateRecord
{
    protected static string $resource = LandingResource::class;

    protected static ?string $title = "Landing qo'shish";
}
