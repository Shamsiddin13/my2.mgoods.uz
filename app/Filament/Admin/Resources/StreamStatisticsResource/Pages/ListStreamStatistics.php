<?php

namespace App\Filament\Admin\Resources\StreamStatisticsResource\Pages;

use App\Filament\Admin\Resources\StreamStatisticsResource;
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

    public function setPage($page, $pageName = 'page'): void
    {
        parent::setPage($page, $pageName);

        $this->dispatch('scroll-to-top');
    }
}
