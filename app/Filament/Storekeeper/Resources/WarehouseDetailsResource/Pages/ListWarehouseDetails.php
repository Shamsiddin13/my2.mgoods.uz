<?php

namespace App\Filament\Storekeeper\Resources\WarehouseDetailsResource\Pages;

use App\Filament\Storekeeper\Resources\WarehouseDetailsResource;
use App\Filament\Storekeeper\Resources\WarehouseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWarehouseDetails extends ListRecords
{
    protected static string $resource = WarehouseDetailsResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),

            Actions\CreateAction::make('createWarehouse')
                ->label('Kirim & Chiqim')
                ->icon('heroicon-o-arrows-up-down')
                ->color('info')
                ->url(WarehouseResource::getUrl('create')),
        ];
    }
}
