<?php

namespace App\Filament\Resources\FinancialLedgerResource\Pages;

use App\Filament\Resources\FinancialLedgerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFinancialLedger extends CreateRecord
{
    protected static string $resource = FinancialLedgerResource::class;
}
