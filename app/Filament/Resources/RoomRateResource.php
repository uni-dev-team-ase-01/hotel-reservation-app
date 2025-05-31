<?php

namespace App\Filament\Resources;

use App\Enum\RateType;
use App\Filament\Resources\RoomRateResource\Pages;
use App\Models\RoomRate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoomRateResource extends Resource
{
    protected static ?string $model = RoomRate::class;

    protected static ?string $navigationGroup = 'Hotel Settings';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('room_id')
                    ->relationship('room', 'id')
                    ->required(),
                Forms\Components\Select::make('rate_type')
                    ->required()
                    ->options(collect(RateType::cases())->mapWithKeys(fn ($case) => [
                        $case->value => $case->getLabel(),
                    ])),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('room.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rate_type'),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListRoomRates::route('/'),
            'create' => Pages\CreateRoomRate::route('/create'),
            'view' => Pages\ViewRoomRate::route('/{record}'),
            'edit' => Pages\EditRoomRate::route('/{record}/edit'),
        ];
    }
}
