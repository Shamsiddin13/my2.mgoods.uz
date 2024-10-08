<?php

namespace App\Filament\Admin\Resources\StatisticsResource\Pages;

use App\Filament\Admin\Resources\DailyStatisticsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStatistics extends EditRecord
{
    protected static string $resource = DailyStatisticsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
