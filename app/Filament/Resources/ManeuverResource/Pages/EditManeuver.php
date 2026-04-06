<?php

namespace App\Filament\Resources\ManeuverResource\Pages;

use App\Filament\Resources\ManeuverResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManeuver extends EditRecord
{
    protected static string $resource = ManeuverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
