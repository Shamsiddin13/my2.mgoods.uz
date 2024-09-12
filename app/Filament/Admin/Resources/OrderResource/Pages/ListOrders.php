<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Filament\Admin\Resources\OrderResource;
use App\Models\Order;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
        return [
            'All' => Tab::make(),
            'Новый' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Новый'))
                ->badge(Order::query()->where('status', 'Новый', )->where('source', auth()->user()->source)->count())->badgeColor('warning')->badgeIcon('heroicon-m-sparkles'),
            'Принят' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Принят'))
                ->badge(Order::query()->where('status', 'Принят')->where('source', auth()->user()->source)->count())->badgeColor('info')->badgeIcon('heroicon-m-check-circle'),
            'Отмена' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Отмена'))
                ->badge(Order::query()->where('status', 'Отмена')->where('source', auth()->user()->source)->count())->badgeColor('danger')->badgeIcon('heroicon-m-x-circle'),
            'В пути' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'В пути'))
                ->badge(Order::query()->where(  'status', 'В пути')->where('source', auth()->user()->source)->count())->badgeColor('info')->badgeIcon('heroicon-m-truck'),
            'Возврат' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Возврат'))
                ->badge(Order::query()->where('status', 'Возврат')->where('source', auth()->user()->source)->count())->badgeColor('danger')->badgeIcon('heroicon-m-x-circle'),
            'Выполнен' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Выполнен'))
                ->badge(Order::query()->where('status', 'Выполнен')->where('source', auth()->user()->source)->count())->badgeColor('success')->badgeIcon('heroicon-m-check-badge'),
            'Доставлен' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Доставлен'))
                ->badge(Order::query()->where('status', 'Доставлен')->where('source', auth()->user()->source)->count())->badgeColor('success')->badgeIcon('heroicon-m-check-badge'),
            'Недозвон' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Недозвон'))
                ->badge(Order::query()->where('status', 'Недозвон')->where('source', auth()->user()->source)->count())->badgeColor('gray')->badgeIcon('heroicon-m-face-frown'),
        ];
    }

    public function setPage($page, $pageName = 'page'): void
    {
        parent::setPage($page, $pageName);

        $this->dispatch('scroll-to-top');
    }

}
