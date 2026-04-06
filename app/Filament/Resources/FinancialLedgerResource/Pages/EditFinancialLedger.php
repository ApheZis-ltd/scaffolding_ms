<?php

namespace App\Filament\Resources\FinancialLedgerResource\Pages;

use App\Filament\Resources\FinancialLedgerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFinancialLedger extends EditRecord
{
    protected static string $resource = FinancialLedgerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
