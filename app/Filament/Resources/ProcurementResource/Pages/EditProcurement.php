<?php

namespace App\Filament\Resources\ProcurementResource\Pages;

use App\Filament\Resources\ProcurementResource;
use App\Filament\Concerns\RequiresTable;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProcurement extends EditRecord
{
    use RequiresTable;

    protected static string $resource = ProcurementResource::class;

    public function mount($record): void
    {
        $this->requireTable('procurements', 'Approvisionnements');

        parent::mount($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
