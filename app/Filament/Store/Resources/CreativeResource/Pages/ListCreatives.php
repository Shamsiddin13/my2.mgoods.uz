<?php

namespace App\Filament\Store\Resources\CreativeResource\Pages;

use App\Filament\Store\Resources\CreativeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCreatives extends ListRecords
{
    protected static string $resource = CreativeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label("Kreativ qo'shish"),
        ];
    }
}
