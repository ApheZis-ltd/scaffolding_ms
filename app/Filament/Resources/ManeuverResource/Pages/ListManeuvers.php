<?php

namespace App\Filament\Resources\ManeuverResource\Pages;

use App\Filament\Resources\ManeuverResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManeuvers extends ListRecords
{
    protected static string $resource = ManeuverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
