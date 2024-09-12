<?php

namespace App\Filament\Admin\Resources\StatisticsResource\Pages;

use App\Filament\Admin\Resources\DailyStatisticsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStatistics extends CreateRecord
{
    protected static string $resource = DailyStatisticsResource::class;
}
