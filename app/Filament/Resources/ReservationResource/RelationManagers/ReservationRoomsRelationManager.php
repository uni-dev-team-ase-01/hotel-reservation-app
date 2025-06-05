<?php

namespace App\Filament\Resources\ReservationResource\RelationManagers;

use App\Filament\Resources\RoomResource;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ReservationRoomsRelationManager extends RelationManager
{
    protected static string $relationship = 'reservationRooms';

    protected static ?string $title = 'Reserved Rooms';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('room_id')
                    ->label('Room')
                    ->options(function () {
                        return Room::with('hotel')
                            ->get()
                            ->mapWithKeys(function ($room) {
                                return [$room->id => $room->hotel->name.' - Room '.$room->room_number.' ('.$room->room_type.')'];
                            });
                    })
                    ->searchable()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('room.room_number')
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
                Tables\Actions\CreateAction::make()
                    ->label('Add Room')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['reservation_id'] = $this->getOwnerRecord()->id;

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Remove Room'),
                Tables\Actions\Action::make('view_room')
                    ->label('View Room')
                    ->icon('heroicon-o-eye')
                    ->url(
                        fn ($record) => $record->room_id
                            ? RoomResource::getUrl('view', ['record' => $record->room_id])
                            : null
                    )
                    ->visible(fn ($record) => $record->room_id !== null),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Remove Rooms'),
                ]),
            ]);
    }
}
