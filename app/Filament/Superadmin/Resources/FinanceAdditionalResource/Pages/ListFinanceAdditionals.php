<?php

namespace App\Filament\Superadmin\Resources\FinanceAdditionalResource\Pages;

use App\Filament\Superadmin\Resources\FinanceAdditionalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFinanceAdditionals extends ListRecords
{
    protected static string $resource = FinanceAdditionalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Create Fin-Additional'),
        ];
    }

}
