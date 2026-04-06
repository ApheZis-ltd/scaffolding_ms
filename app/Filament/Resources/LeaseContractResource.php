<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaseContractResource\Pages;
use App\Filament\Resources\LeaseContractResource\RelationManagers;
use App\Models\LeaseContract;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeaseContractResource extends Resource
{
    protected static ?string $model = LeaseContract::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Agreement Details')
                    ->schema([
                        Forms\Components\Select::make('contact_id')
                            ->relationship('contact', 'name', fn ($query) => $query->where('type', 'borrower'))
                            ->searchable()
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'closed' => 'Closed',
                                'pending_review' => 'Pending Review',
                            ])
                            ->default('active')
                            ->required()
                            ->native(false),
                        Forms\Components\DatePicker::make('start_date')
                            ->default(now())
                            ->required()
                            ->native(false),
                        Forms\Components\DatePicker::make('expected_return_date')
                            ->required()
                            ->native(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('contact.name')
                    ->label('Borrower')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'closed' => 'gray',
                        'pending_review' => 'warning',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->formatStateUsing(fn ($state) => $state ? $state->format('Y-m-d') : '-')
                    ->sortable()
                    ->fontFamily('mono'),
                Tables\Columns\TextColumn::make('expected_return_date')
                    ->formatStateUsing(fn ($state) => $state ? $state->format('Y-m-d') : '-')
                    ->sortable()
                    ->fontFamily('mono')
                    ->color(fn ($record): string => $record->expected_return_date < now() && $record->status === 'active' ? 'danger' : 'gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'closed' => 'Closed',
                        'pending_review' => 'Pending Review',
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
            'index' => Pages\ListLeaseContracts::route('/'),
            'create' => Pages\CreateLeaseContract::route('/create'),
            'edit' => Pages\EditLeaseContract::route('/{record}/edit'),
        ];
    }
}
