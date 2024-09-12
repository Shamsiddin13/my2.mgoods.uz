<?php

namespace App\Filament\Admin\Resources\StreamStatisticsResource\Pages;

use App\Filament\Admin\Resources\StreamStatisticsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStreamStatistics extends EditRecord
{
    protected static string $resource = StreamStatisticsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
