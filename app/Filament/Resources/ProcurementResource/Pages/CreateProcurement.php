<?php

namespace App\Filament\Resources\ProcurementResource\Pages;

use App\Filament\Resources\ProcurementResource;
use App\Filament\Concerns\RequiresTable;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProcurement extends CreateRecord
{
    use RequiresTable;

    protected static string $resource = ProcurementResource::class;

    public function mount(): void
    {
        $this->requireTable('procurements', 'Approvisionnements');

        parent::mount();
    }
}
