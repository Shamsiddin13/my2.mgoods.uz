<?php

namespace App\Filament\Storekeeper\Resources\WarehouseDetailsResource\Pages;

use App\Filament\Storekeeper\Resources\WarehouseDetailsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWarehouseDetails extends EditRecord
{
    protected static string $resource = WarehouseDetailsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
