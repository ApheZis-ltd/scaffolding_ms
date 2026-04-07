<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ManeuverResource\Pages;
use App\Filament\Resources\ManeuverResource\RelationManagers;
use App\Models\Maneuver;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ManeuverResource extends Resource
{
    protected static ?string $model = Maneuver::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Détails du mouvement')
                    ->schema([
                        Forms\Components\Select::make('lease_contract_id')
                            ->label('Contrat de location')
                            ->relationship('leaseContract', 'id')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('equipment_id')
                            ->label('Équipement')
                            ->relationship('equipment', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Quantité')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->extraInputAttributes(['class' => 'font-mono']),
                        Forms\Components\Select::make('type')
                            ->label('Type')
                            ->options([
                                'rent' => 'Sortie (Rent)',
                                'lend' => 'Prêt (Lend)',
                                'return' => 'Retour (Return)',
                                'damage' => 'Dommage (Damage)',
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('status')
                            ->label('Statut')
                            ->options([
                                'complete' => 'Complet',
                                'returned' => 'Retourné',
                                'flagged_review' => 'À vérifier',
                            ])
                            ->default('complete')
                            ->required()
                            ->native(false),
                        Forms\Components\Textarea::make('manager_comments')
                            ->label('Commentaires (manager)')
                            ->columnSpanFull()
                            ->rows(3),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('leaseContract.id')
                    ->label('Lease ID')
                    ->formatStateUsing(fn ($state) => $state)
                    ->sortable(),
                Tables\Columns\TextColumn::make('equipment.name')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->formatStateUsing(fn ($state) => number_format((float)$state, 0))
                    ->fontFamily('mono')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'damage' => 'danger',
                        'return' => 'info',
                        'rent', 'lend' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'rent' => 'Rent',
                        'lend' => 'Lend',
                        'return' => 'Return',
                        'damage' => 'Damage',
                        default => $state,
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'complete' => 'success',
                        'returned' => 'info',
                        'flagged_review' => 'warning',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('manager.name')
                    ->label('Manager')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('manager_comments')
                    ->label('Commentaires')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->formatStateUsing(fn ($state) => $state ? $state->format('Y-m-d H:i') : '-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->formatStateUsing(fn ($state) => $state ? $state->format('Y-m-d H:i') : '-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'rent' => 'Rent',
                        'lend' => 'Lend',
                        'return' => 'Return',
                        'damage' => 'Damage',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'complete' => 'Complet',
                        'returned' => 'Retourné',
                        'flagged_review' => 'À vérifier',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListManeuvers::route('/'),
            'create' => Pages\CreateManeuver::route('/create'),
            'edit' => Pages\EditManeuver::route('/{record}/edit'),
        ];
    }
}
