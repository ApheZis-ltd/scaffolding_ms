<?php

namespace App\Filament\Concerns;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Schema;

trait RequiresTable
{
    protected function requireTable(string $table, string $label, string $redirectUrl = null): void
    {
        if (Schema::hasTable($table)) {
            return;
        }

        Notification::make()
            ->title($label . ' indisponible')
            ->body('Initialisation en cours. Veuillez contacter un administrateur si le problème persiste.')
            ->danger()
            ->send();

        $this->redirect($redirectUrl ?? url('/admin'));
    }
}

