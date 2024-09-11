<?php

namespace App\Filament\Resources\ProfitLossStatisticsResource\Pages;

use App\Filament\Resources\ProfitLossStatisticsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProfitLossStatistics extends EditRecord
{
    protected static string $resource = ProfitLossStatisticsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
