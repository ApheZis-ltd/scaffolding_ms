<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EquipmentResource\Pages;
use App\Filament\Resources\EquipmentResource\RelationManagers;
use App\Models\Equipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EquipmentResource extends Resource
{
    protected static ?string $model = Equipment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Core Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->extraInputAttributes(['class' => 'font-mono']),
                    ])->columns(2),

                Forms\Components\Section::make('Inventory Levels')
                    ->schema([
                        Forms\Components\TextInput::make('total_stock')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->extraInputAttributes(['class' => 'font-mono']),
                        Forms\Components\TextInput::make('available_stock')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->extraInputAttributes(['class' => 'font-mono']),
                    ])->columns(2),

                Forms\Components\Section::make('Technical Metadata')
                    ->schema([
                        Forms\Components\KeyValue::make('metadata')
                            ->label('Equipment Specifications')
                            ->keyLabel('Property')
                            ->valueLabel('Value'),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->weight('bold')
                    ->size('lg'),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->fontFamily('mono')
                    ->color('gray'),
                Tables\Columns\TextColumn::make('total_stock')
                    ->formatStateUsing(fn ($state) => number_format((float)$state, 0))
                    ->sortable()
                    ->fontFamily('mono'),
                Tables\Columns\TextColumn::make('available_stock')
                    ->formatStateUsing(fn ($state) => number_format((float)$state, 0))
                    ->sortable()
                    ->fontFamily('mono')
                    ->color(fn (int $state): string => $state < 5 ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->formatStateUsing(fn ($state) => $state ? $state->format('Y-m-d H:i') : '-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            RelationManagers\ManeuversRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEquipment::route('/'),
            'create' => Pages\CreateEquipment::route('/create'),
            'edit' => Pages\EditEquipment::route('/{record}/edit'),
        ];
    }
}
