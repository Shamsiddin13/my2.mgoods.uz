<?php

namespace App\Filament\Resources\ProfitLossStatisticsResource\Pages;

use App\Filament\Resources\ProfitLossStatisticsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListProfitLossStatistics extends ListRecords
{
    protected static string $resource = ProfitLossStatisticsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTableRecordKey(Model $record): string
    {
        return uniqid();
    }
}
