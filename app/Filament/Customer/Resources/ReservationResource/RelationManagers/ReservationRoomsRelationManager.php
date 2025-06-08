<?php

namespace App\Filament\Customer\Resources\ReservationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ReservationRoomsRelationManager extends RelationManager
{
    protected static string $relationship = 'reservationRooms';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('room_number')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('room_number')
            ->columns([
                Tables\Columns\TextColumn::make('room.hotel.name')
                    ->label('Hotel')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('room.room_number')
                    ->label('Room Number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('room.room_type')
                    ->label('Room Type')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('room.occupancy')
                    ->label('Occupancy')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('room.location')
                    ->label('Location')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Reserved On')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('room.room_type')
                    ->label('Room Type')
                    ->relationship('room', 'room_type'),

                Tables\Filters\SelectFilter::make('room.hotel')
                    ->label('Hotel')
                    ->relationship('room.hotel', 'name'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
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
