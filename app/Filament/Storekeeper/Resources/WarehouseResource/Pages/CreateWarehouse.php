<?php

namespace App\Filament\Storekeeper\Resources\WarehouseResource\Pages;

use App\Filament\Storekeeper\Resources\WarehouseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWarehouse extends CreateRecord
{
    protected static string $resource = WarehouseResource::class;

    protected static ?string $navigationLabel = 'Omborga Kirim & Chiqim';
}
