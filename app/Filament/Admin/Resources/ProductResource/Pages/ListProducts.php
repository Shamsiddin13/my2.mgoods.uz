<?php

namespace App\Filament\Admin\Resources\ProductResource\Pages;

use App\Filament\Admin\Resources\ProductResource;
use App\Models\Product;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    protected function getTableQuery(): Builder
    {
        return Product::query()
            ->where('products.status', "Ochiq")
            ->join('landing', 'products.article', '=', 'landing.article') // Joining the landing table
            ->orderBy('landing.created_at', 'DESC') // Sorting by landing's created_at in descending order
            ->select('products.*'); // Ensure you're selecting only product columns to avoid conflicts
    }


    public function setPage($page, $pageName = 'page'): void
    {
        parent::setPage($page, $pageName);

        $this->dispatch('scroll-to-top');
    }

    public function getTableRecordKey(Model $record): string
    {
        return uniqid();
    }


}
