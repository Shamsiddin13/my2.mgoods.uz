<?php

namespace App\Filament\Store\Resources\WarehouseHistoryResource\Pages;

use App\Filament\Store\Resources\WarehouseHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWarehouseHistories extends ListRecords
{
    protected static string $resource = WarehouseHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }
}
