<?php

namespace App\Filament\Resources\StreamStatisticsResource\Pages;

use App\Filament\Resources\StreamStatisticsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListStreamStatistics extends ListRecords
{
    protected static string $resource = StreamStatisticsResource::class;

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
