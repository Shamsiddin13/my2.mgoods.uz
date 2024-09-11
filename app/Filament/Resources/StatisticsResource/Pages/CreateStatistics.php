<?php

namespace App\Filament\Resources\StatisticsResource\Pages;

use App\Filament\Resources\DailyStatisticsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStatistics extends CreateRecord
{
    protected static string $resource = DailyStatisticsResource::class;
}
