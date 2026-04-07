<?php

namespace App\Filament\Resources\LeaseContractResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Maneuver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManeuversRelationManager extends RelationManager
{
    protected static string $relationship = 'maneuvers';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('equipment_id')
                    ->relationship('equipment', 'name')
                    ->searchable()
                    ->required()
                    ->native(false),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\Select::make('type')
                    ->options([
                        'rent' => 'Rent',
                        'lend' => 'Lend',
                        'return' => 'Return',
                        'damage' => 'Damage Flag',
                    ])
                    ->required()
                    ->native(false),
                Forms\Components\Select::make('status')
                    ->options([
                        'complete' => 'Complete',
                        'returned' => 'Returned',
                        'flagged_review' => 'Flagged for Review',
                    ])
                    ->default('complete')
                    ->required()
                    ->native(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('equipment.name')
            ->columns([
                Tables\Columns\TextColumn::make('equipment.name')
                    ->weight('bold')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->formatStateUsing(fn ($state) => number_format((float)$state, 0))
                    ->sortable()
                    ->fontFamily('mono'),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'rent' => 'info',
                        'lend' => 'info',
                        'return' => 'success',
                        'damage' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'complete' => 'success',
                        'returned' => 'info',
                        'flagged_review' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('complete_review')
                    ->label('Complete Review')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'flagged_review')
                    ->form([
                        Forms\Components\Textarea::make('manager_comments')
                            ->label('Assessment Comments')
                            ->required(),
                        Forms\Components\Toggle::make('maintenance_required')
                            ->label('Maintenance Required?')
                            ->live()
                            ->default(false),
                        Forms\Components\TextInput::make('maintenance_cost')
                            ->label('Maintenance Cost')
                            ->numeric()
                            ->prefix(env('APP_CURRENCY', 'RWF'))
                            ->visible(fn (Forms\Get $get) => $get('maintenance_required'))
                            ->required(fn (Forms\Get $get) => $get('maintenance_required')),
                    ])
                    ->action(function (array $data, Maneuver $record) {
                        \DB::transaction(function () use ($data, $record) {
                            $record->update([
                                'status' => 'complete',
                                'type' => $data['maintenance_required'] ? 'damage' : 'return',
                                'manager_id' => auth()->id(),
                                'manager_comments' => $data['manager_comments'],
                            ]);

                            if ($data['maintenance_required']) {
                                \App\Models\FinancialLedger::create([
                                    'lease_contract_id' => $record->lease_contract_id,
                                    'total_value' => $data['maintenance_cost'],
                                    'balance' => $data['maintenance_cost'],
                                    'type' => 'maintenance',
                                    'manager_id' => auth()->id(),
                                ]);
                            }

                            // Check if all maneuvers for this lease are complete/returned
                            $allDone = $record->leaseContract->maneuvers()->where('status', '!=', 'complete')->count() === 0;
                            if ($allDone) {
                                $record->leaseContract->update(['status' => 'closed']);
                            }
                        });
                    }),
                
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
