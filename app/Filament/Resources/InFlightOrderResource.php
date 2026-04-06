<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InFlightOrderResource\Pages;
use App\Filament\Resources\InFlightOrderResource\RelationManagers;
use App\Models\InFlightOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InFlightOrderResource extends Resource
{
    protected static ?string $model = InFlightOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Logistics & Pricing')
                    ->schema([
                        Forms\Components\Select::make('vendor_id')
                            ->relationship('vendor', 'name', fn ($query) => $query->where('type', 'vendor'))
                            ->searchable()
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('equipment_id')
                            ->relationship('equipment', 'name')
                            ->searchable()
                            ->required()
                            ->native(false),
                        Forms\Components\TextInput::make('qty')
                            ->label('Quantity')
                            ->required()
                            ->numeric()
                            ->extraInputAttributes(['class' => 'font-mono']),
                        Forms\Components\TextInput::make('pricing')
                            ->label('Unit Pricing')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->extraInputAttributes(['class' => 'font-mono']),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                            ])
                            ->default('pending')
                            ->required()
                            ->native(false),
                        Forms\Components\TextInput::make('site_details')
                            ->placeholder('Site location / delivery notes')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vendor.name')
                    ->label('Supplier')
                    ->weight('bold')
                    ->sortable(),
                Tables\Columns\TextColumn::make('equipment.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('qty')
                    ->label('Qty')
                    ->numeric()
                    ->fontFamily('mono')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'shipped' => 'info',
                        'delivered' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('pricing')
                    ->money('USD')
                    ->fontFamily('mono')
                    ->sortable(),
                Tables\Columns\TextColumn::make('site_details')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
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
            'index' => Pages\ListInFlightOrders::route('/'),
            'create' => Pages\CreateInFlightOrder::route('/create'),
            'edit' => Pages\EditInFlightOrder::route('/{record}/edit'),
        ];
    }
}
