<?php

namespace App\Filament\Resources\StatisticsResource\Pages;

use App\Filament\Resources\DailyStatisticsResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListStatistics extends ListRecords
{
    protected static string $resource = DailyStatisticsResource::class;

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

//    public function getTabs():array
//    {
//        return [
//            'Kunlik' => Tab::make(),
//            'Oqim' => Tab::make(),
//            'Foyda & Zarar'=>Tab::make(),
//        ];
//    }

}
