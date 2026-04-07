<?php

namespace App\Filament\Resources\ProcurementResource\Pages;

use App\Filament\Resources\ProcurementResource;
use App\Filament\Concerns\RequiresTable;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProcurements extends ListRecords
{
    use RequiresTable;

    protected static string $resource = ProcurementResource::class;

    public function mount(): void
    {
        $this->requireTable('procurements', 'Approvisionnements');

        parent::mount();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
