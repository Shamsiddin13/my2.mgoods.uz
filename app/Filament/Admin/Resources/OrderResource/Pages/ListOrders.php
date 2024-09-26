<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Filament\Admin\Resources\OrderResource;
use App\Models\Order;
use App\Models\RequestFin;
use Filament\Actions\Action;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
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
    protected function getTableQuery(): Builder
    {
        // Get the authenticated user
        $user = Auth::user();

        // Return the query, filtering orders by the user's source
        return Order::query()
            ->where('source', $user->source);
    }
    public function getTabs():array
    {
        if (auth()->user()->source === 'btd'){
            return [
                'All' => Tab::make(),
                'New' => Tab::make()
                    ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'new'))
                    ->badge(Order::query()->where('status', 'new', )->where('source', auth()->user()->source)->count())->badgeColor('warning')->badgeIcon('heroicon-m-sparkles'),
                'Updated' => Tab::make()
                    ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'updated'))
                    ->badge(Order::query()->where('status', 'updated')->where('source', auth()->user()->source)->count())->badgeColor('info')->badgeIcon('heroicon-m-arrow-path'),
                "Recall" => Tab::make()
                    ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'recall'))
                    ->badge(Order::query()->where('status', 'recall')->where('source', auth()->user()->source)->count())->badgeColor('gray')->badgeIcon('heroicon-m-phone-arrow-up-right'),
                "Call late" => Tab::make()
                    ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'call_late'))
                    ->badge(Order::query()->where('status', 'call_late')->where('source', auth()->user()->source)->count())->badgeColor('gray')->badgeIcon('heroicon-m-phone'),
                'Cancel' => Tab::make()
                    ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'cancel'))
                    ->badge(Order::query()->where('status', 'cancel')->where('source', auth()->user()->source)->count())->badgeColor('danger')->badgeIcon('heroicon-m-x-circle'),
//                'Accept' => Tab::make()
//                    ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'accept'))
//                    ->badge(Order::query()->where('status', 'accept')->where('source', auth()->user()->source)->count())->badgeColor('info')->badgeIcon('heroicon-m-check-circle'),
                'Send' => Tab::make()
                    ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'send'))
                    ->badge(Order::query()->where(  'status', 'send')->where('source', auth()->user()->source)->count())->badgeColor('info')->badgeIcon('heroicon-m-truck'),
                'Delivered' => Tab::make()
                    ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'delivered'))
                    ->badge(Order::query()->where('status', 'delivered')->where('source', auth()->user()->source)->count())->badgeColor('success')->badgeIcon('heroicon-m-check-badge'),
                'Returned' => Tab::make()
                    ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'returned'))
                    ->badge(Order::query()->where('status', 'returned')->where('source', auth()->user()->source)->count())->badgeColor('danger')->badgeIcon('heroicon-m-x-circle'),
                'Sold' => Tab::make()
                    ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'sold'))
                    ->badge(Order::query()->where('status', 'sold')->where('source', auth()->user()->source)->count())->badgeColor('success')->badgeIcon('heroicon-m-check-badge'),
            ];
        }
        else{
            return [];
        }
    }

    public function setPage($page, $pageName = 'page'): void
    {
        parent::setPage($page, $pageName);

        $this->dispatch('scroll-to-top');
    }

    public function newOrdersToday(): Action
    {
        $newOrdersToday = Order::query()->where('source', auth()->user()->source)->wheredate('createdAt', now())->where('status', 'new')->count();

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
