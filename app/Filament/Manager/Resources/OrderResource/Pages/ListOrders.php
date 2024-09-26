<?php

namespace App\Filament\Manager\Resources\OrderResource\Pages;

use App\Filament\Manager\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\HtmlString;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

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

    public function newOrdersToday(): Action
    {
        $newOrdersToday = Order::query()->where('manager', auth()->user()->manager)->wheredate('createdAt', today())->where('status', 'new')->count();

        return Action::make('newOrdersToday')
            ->visible($newOrdersToday > 0)
            ->modalSubmitActionLabel('Tushunarli !')
            ->action(null)
            ->color('success')
            ->modalCancelAction(false)
            ->modalHeading("Bugun Yangi buyurtmalar kelib tushdi")
            ->modalDescription(new HtmlString("Bugunlik kunda <strong>$newOrdersToday</strong> ta <strong>Yangi</strong> Buyurtmalar bor!"));
    }
}
