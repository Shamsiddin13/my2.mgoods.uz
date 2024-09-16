<?php

namespace App\Filament\Storekeeper\Resources\WarehouseResource\Pages;

use App\Filament\Storekeeper\Resources\WarehouseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWarehouses extends ListRecords
{
    protected static string $resource = WarehouseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Kirim & Chiqim')
        ];
    }
}
