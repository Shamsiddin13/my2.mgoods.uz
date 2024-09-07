<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Filament\Resources\StreamResource;
use App\Filament\Resources\WarehouseHistoryReturnResource;
use App\Filament\Resources\YesResource\Pages\ProductPage;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make('createStream')
//                ->label('Oqim Yaratish')
//                ->icon('heroicon-o-arrow-path')
//                ->color('info')
//                ->url(StreamResource::getUrl('create')),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return Product::query()
            ->where('status', "Ochiq");
    }
}
