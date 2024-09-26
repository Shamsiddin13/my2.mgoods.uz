<?php

namespace App\Filament\Landing\Resources\LandingResource\Pages;

use App\Filament\Landing\Resources\LandingResource;
use App\Filament\Store\Resources\CreativeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLandings extends ListRecords
{
    protected static string $resource = LandingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label("Landing qo'shish")
                ->icon('heroicon-s-square-3-stack-3d')
                ->color('warning'),

            Actions\CreateAction::make('createCreative')
                ->label("Kreativ qo'shish")
                ->icon('heroicon-s-paper-airplane')
                ->color('info')
                ->url(CreativeResource::getUrl('create')),
        ];
    }

    public function setPage($page, $pageName = 'page'): void
    {
        parent::setPage($page, $pageName);

        $this->dispatch('scroll-to-top');
    }
}
