<?php

namespace App\Filament\Resources\InFlightOrderResource\Pages;

use App\Filament\Resources\InFlightOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInFlightOrder extends EditRecord
{
    protected static string $resource = InFlightOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
