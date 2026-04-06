<?php

namespace App\Filament\Resources\InFlightOrderResource\Pages;

use App\Filament\Resources\InFlightOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInFlightOrder extends CreateRecord
{
    protected static string $resource = InFlightOrderResource::class;
}
