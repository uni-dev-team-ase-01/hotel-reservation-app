<?php

namespace App\Filament\Resources;

use App\Enum\SriLankanDistrict;
use App\Enum\StarRating;
use App\Enum\UserRoleType;
use App\Filament\Resources\HotelResource\Pages;
use App\Filament\Resources\HotelResource\RelationManagers\HotelServicesRelationManager;
use App\Filament\Resources\HotelResource\RelationManagers\RoomsRelationManager;
use App\Filament\Resources\HotelResource\RelationManagers\UserHotelsRelationManager;
use App\Models\Hotel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class HotelResource extends Resource
{
    protected static ?string $model = Hotel::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('address')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('district')
                    ->label('District')
                    ->options(SriLankanDistrict::options())
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('type')
                    ->label('Hotel Type')
                    ->options(
                        [
                            'Hotel' => 'Hotel',
                            'Apartment' => 'Apartment',
                            'Resort' => 'Resort',
                            'Villa' => 'Villa',
                            'Lodge' => 'Lodge',
                            'Guest House' => 'Guest House',
                            'Cottage' => 'Cottage',
                            'Beach Hut' => 'Beach Hut',
                            'Farm House' => 'Farm House',
                            'Luxury' => 'Luxury',
                            'Budget' => 'Budget'
                        ]
                    )
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('star_rating')
                    ->label('Star Rating')
                    ->options(StarRating::options())
                    ->required(),
                Forms\Components\FileUpload::make('images')
                    ->disk('public')
                    ->directory('uploads')
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(2048)
                    ->multiple(false),
                Forms\Components\TextInput::make('website')
                    ->maxLength(255),
                Forms\Components\Toggle::make('active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('star_rating')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('images')
                    ->searchable(),
                Tables\Columns\TextColumn::make('website')
                    ->searchable(),
                Tables\Columns\TextColumn::make('district')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
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
            RoomsRelationManager::class,
            // ServicesRelationManager::class,
            HotelServicesRelationManager::class,
            UserHotelsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHotels::route('/'),
            'create' => Pages\CreateHotel::route('/create'),
            'view' => Pages\ViewHotel::route('/{record}'),
            'edit' => Pages\EditHotel::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        if ($user->hasAnyRole([UserRoleType::HOTEL_CLERK->value, UserRoleType::HOTEL_MANAGER->value])) {
            $assignedHotelIds = $user->userHotels->pluck('hotel_id');

            return parent::getEloquentQuery()
                ->whereIn('id', $assignedHotelIds);
        } elseif ($user->hasRole(UserRoleType::SUPER_ADMIN->value)) {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()->whereRaw('1 = 0');
    }
}
