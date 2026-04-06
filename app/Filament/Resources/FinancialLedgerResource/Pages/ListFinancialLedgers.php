<?php

namespace App\Filament\Resources\FinancialLedgerResource\Pages;

use App\Filament\Resources\FinancialLedgerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFinancialLedgers extends ListRecords
{
    protected static string $resource = FinancialLedgerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
