<?php

namespace App\Filament\Store\Resources\WarehouseResource\Pages;

use App\Filament\Store\Resources\WarehouseResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListWarehouses extends ListRecords
{
    protected static string $resource = WarehouseResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }

    public function getTableRecordKey(Model $record): string
    {
        return uniqid();
    }
}
