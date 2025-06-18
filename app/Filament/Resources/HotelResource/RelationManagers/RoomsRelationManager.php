<?php

namespace App\Filament\Resources\HotelResource\RelationManagers;

use App\Enum\RoomType;
use App\Enum\UserRoleType;
use App\Filament\Resources\RoomResource;
use App\Models\Hotel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class RoomsRelationManager extends RelationManager
{
    protected static string $relationship = 'rooms';

    public function form(Form $form): Form
    {
        $user = auth()->user();
        $isHotelStaff = $user->hasAnyRole([UserRoleType::HOTEL_CLERK->value, UserRoleType::HOTEL_MANAGER->value]);
        $userHotels = $isHotelStaff ? $user->userHotels->pluck('hotel_id') : collect();

        return $form
            ->schema([
                Forms\Components\Select::make('hotel_id')
                    ->label('Hotel')
                    ->relationship('hotel', 'name')
                    ->options(function () use ($isHotelStaff, $userHotels) {
                        if ($isHotelStaff) {
                            return Hotel::whereIn('id', $userHotels)->pluck('name', 'id');
                        }

                        return Hotel::pluck('name', 'id');
                    })
                    ->default(function () use ($isHotelStaff, $userHotels) {
                        if ($isHotelStaff && $userHotels->count() === 1) {
                            return $userHotels->first();
                        }

                        return null;
                    })
                    ->required()
                    ->searchable()
                    ->disabled($isHotelStaff && $userHotels->count() === 1)
                    ->dehydrated(),
                Forms\Components\TextInput::make('room_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('room_type')
                    ->required()
                    ->options(collect(RoomType::cases())->mapWithKeys(fn($case) => [
                        $case->value => $case->getLabel(),
                    ])),
                Forms\Components\TextInput::make('occupancy')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('location')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('images')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('room_number')
            ->columns([
                Tables\Columns\TextColumn::make('room_number')
                    ->label('Room Number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('room_type')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('occupancy')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('images')
                    ->searchable(),
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('view_room')
                    ->label('View Room')
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => RoomResource::getUrl('view', ['record' => $record])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
