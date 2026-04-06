<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinancialLedgerResource\Pages;
use App\Filament\Resources\FinancialLedgerResource\RelationManagers;
use App\Models\FinancialLedger;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FinancialLedgerResource extends Resource
{
    protected static ?string $model = FinancialLedger::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Transaction Details')
                    ->schema([
                        Forms\Components\Select::make('lease_contract_id')
                            ->relationship('leaseContract', 'id')
                            ->label('Lease Contract ID')
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('type')
                            ->options([
                                'invoice' => 'Invoice',
                                'credit' => 'Credit',
                                'maintenance' => 'Maintenance Fee',
                            ])
                            ->required()
                            ->native(false),
                    ])->columns(2),

                Forms\Components\Section::make('Financials')
                    ->schema([
                        Forms\Components\TextInput::make('total_value')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->extraInputAttributes(['class' => 'font-mono']),
                        Forms\Components\TextInput::make('deposits_paid')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->extraInputAttributes(['class' => 'font-mono']),
                        Forms\Components\TextInput::make('balance')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->extraInputAttributes(['class' => 'font-mono']),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('leaseContract.contact.name')
                    ->label('Entity')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'invoice' => 'info',
                        'credit' => 'success',
                        'maintenance' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('total_value')
                    ->formatStateUsing(fn ($state) => '$' . number_format((float)$state, 2))
                    ->fontFamily('mono')
                    ->sortable(),
                Tables\Columns\TextColumn::make('balance')
                    ->formatStateUsing(fn ($state) => '$' . number_format((float)$state, 2))
                    ->fontFamily('mono')
                    ->weight('bold')
                    ->color(fn ($state): string => (float)$state > 0 ? 'danger' : 'success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->formatStateUsing(fn ($state) => $state ? $state->format('Y-m-d H:i') : '-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'invoice' => 'Invoice',
                        'credit' => 'Credit',
                        'maintenance' => 'Maintenance Fee',
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
            'index' => Pages\ListFinancialLedgers::route('/'),
            'create' => Pages\CreateFinancialLedger::route('/create'),
            'edit' => Pages\EditFinancialLedger::route('/{record}/edit'),
        ];
    }
}
