<?php

namespace App\Filament\Superadmin\Resources\FinanceAdditionalResource\Pages;

use App\Filament\Superadmin\Resources\FinanceAdditionalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFinanceAdditional extends EditRecord
{
    protected static string $resource = FinanceAdditionalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
