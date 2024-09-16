<?php

namespace App\Filament\Admin\Resources\ProfitLossStatisticsResource\Pages;

use App\Filament\Admin\Resources\ProfitLossStatisticsResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\View\Components\Modal;
use Filament\View\LegacyComponents\Page;
use Illuminate\Database\Eloquent\Model;

class ListProfitLossStatistics extends ListRecords
{
    protected static string $resource = ProfitLossStatisticsResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
            Action::make('updateTargetPage')
                ->label('Harajat summa')
                ->url(route('filament.admin.resources.profit-loss-statistics.update-target'))
                ->icon('heroicon-o-pencil') // Optional: add an icon
                ->color('primary'), // Optional: set button color
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
