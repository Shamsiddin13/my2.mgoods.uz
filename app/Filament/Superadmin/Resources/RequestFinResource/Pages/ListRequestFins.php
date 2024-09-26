<?php

namespace App\Filament\Superadmin\Resources\RequestFinResource\Pages;

use App\Filament\Superadmin\Resources\RequestFinResource;
use App\Models\RequestFin;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class ListRequestFins extends ListRecords
{
    protected static string $resource = RequestFinResource::class;

    public $defaultAction = 'newRequestFinToday';

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }

    public function getTabs():array
    {
        return [
            'All' => Tab::make(),
            'New' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'new'))
                ->badge(RequestFin::query()->where('status', 'new', )->count())->badgeColor('warning')->badgeIcon('heroicon-m-clock'),
            'Approved' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'approved'))
                ->badge(RequestFin::query()->where('status', 'approved')->count())->badgeColor('success')->badgeIcon('heroicon-m-check-badge'),
            'Cancel' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'cancel'))
                ->badge(RequestFin::query()->where('status', 'cancel', )->count())->badgeColor('danger')->badgeIcon('heroicon-m-x-circle'),

        ];
    }

    public function newRequestFinToday(): Actions\Action
    {
        $newRequestFinToday = RequestFin::query()->wheredate('created_at', today())->where('status', 'new')->count();

        return Actions\Action::make('newRequestFinToday')
                ->visible($newRequestFinToday > 0)
                ->modalSubmitActionLabel('Got it !')
                ->action(null)
                ->color('success')
                ->modalCancelAction(false)
                ->modalHeading('New Requests Today')
                ->modalDescription(new HtmlString("There are <strong>$newRequestFinToday</strong> new requests received today!"));
    }

    public function setPage($page, $pageName = 'page'): void
    {
        parent::setPage($page, $pageName);

        $this->dispatch('scroll-to-top');
    }
}
