<?php

namespace App\Filament\Admin\Resources\StreamResource\Pages;

use App\Filament\Admin\Resources\StreamResource;
use Filament\Resources\Pages\ListRecords;

class ListStreams extends ListRecords
{
    protected static string $resource = StreamResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }

    public function setPage($page, $pageName = 'page'): void
    {
        parent::setPage($page, $pageName);

        $this->dispatch('scroll-to-top');
    }
}
