<?php

namespace App\Filament\Store\Resources\ProductResource\Pages;

use App\Filament\Store\Resources\CreativeResource;
use App\Filament\Store\Resources\LandingResource;
use App\Filament\Store\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label("Mahsulot qo'shish")
                ->icon('heroicon-s-square-3-stack-3d')
                ->color('warning')
                ->url(LandingResource::getUrl('create')),

            Actions\CreateAction::make('createCreative')
                ->label("Kreativ qo'shish")
                ->icon('heroicon-s-paper-airplane')
                ->color('info')
                ->url(CreativeResource::getUrl('create')),

        ];
    }
}
