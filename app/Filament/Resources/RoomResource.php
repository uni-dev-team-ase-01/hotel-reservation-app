<?php

namespace App\Filament\Resources;

use App\Enum\RoomType;
use App\Enum\UserRoleType;
use App\Filament\Resources\RoomResource\Pages;
use App\Filament\Resources\RoomResource\RelationManagers\RatesRelationManager;
use App\Models\Hotel;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    public static function form(Form $form): Form
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
                    ->options(collect(RoomType::cases())->mapWithKeys(fn ($case) => [
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('hotel.name')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('room_number')
                    ->searchable(),
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
            RatesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'view' => Pages\ViewRoom::route('/{record}'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        if ($user->hasAnyRole([UserRoleType::HOTEL_CLERK->value, UserRoleType::HOTEL_MANAGER->value])) {
            $assignedHotelIds = $user->userHotels->pluck('hotel_id');

            return parent::getEloquentQuery()
                ->whereIn('hotel_id', $assignedHotelIds);
        } elseif ($user->hasRole(UserRoleType::SUPER_ADMIN->value)) {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()->whereRaw('1 = 0');
    }
}
