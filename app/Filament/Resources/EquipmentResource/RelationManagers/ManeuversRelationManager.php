<?php

namespace App\Filament\Resources\EquipmentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManeuversRelationManager extends RelationManager
{
    protected static string $relationship = 'maneuvers';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('lease_contract_id')
                    ->relationship('leaseContract', 'id')
                    ->label('Lease ID')
                    ->searchable()
                    ->required()
                    ->native(false),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->fontFamily('mono'),
                Forms\Components\Select::make('type')
                    ->options([
                        'rent' => 'Rent',
                        'lend' => 'Lend',
                        'return' => 'Return',
                        'damage' => 'Damage Report',
                    ])
                    ->required()
                    ->native(false),
                Forms\Components\Select::make('status')
                    ->options([
                        'complete' => 'Complete',
                        'returned' => 'Returned',
                        'flagged_review' => 'Flagged for Review',
                    ])
                    ->required()
                    ->native(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('lease_contract_id')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M d, Y H:i')
                    ->fontFamily('mono')
                    ->sortable(),
                Tables\Columns\TextColumn::make('leaseContract.contact.name')
                    ->label('Borrower / Entitiy')
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->fontFamily('mono')
                    ->sortable(),
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
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                // Read-only history mostly
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
