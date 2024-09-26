<?php

namespace App\Filament\Store\Resources\CreativeResource\Pages;

use App\Filament\Store\Resources\CreativeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCreative extends EditRecord
{
    protected static string $resource = CreativeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
