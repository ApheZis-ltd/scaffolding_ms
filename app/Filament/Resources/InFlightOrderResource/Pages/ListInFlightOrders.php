<?php

namespace App\Filament\Resources\InFlightOrderResource\Pages;

use App\Filament\Resources\InFlightOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInFlightOrders extends ListRecords
{
    protected static string $resource = InFlightOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
