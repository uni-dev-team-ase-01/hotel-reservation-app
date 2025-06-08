<?php

namespace App\Filament\Resources;

use App\Enum\PaymentStatus;
use App\Enum\UserRoleType;
use App\Filament\Resources\BillResource\Pages;
use App\Filament\Resources\BillResource\RelationManagers\BillServicesRelationManager;
use App\Filament\Resources\BillResource\RelationManagers\PaymentsRelationManager;
use App\Models\Bill;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BillResource extends Resource
{
    protected static ?string $model = Bill::class;

    protected static ?string $navigationGroup = 'Financial';

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('reservation_id')
                    ->relationship('reservation', 'id')
                    ->required(),
                Forms\Components\TextInput::make('room_charges')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('extra_charges')
                    ->numeric(),
                Forms\Components\TextInput::make('discount')
                    ->numeric(),
                Forms\Components\TextInput::make('taxes')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('total_amount')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('payment_status')
                    ->required()
                    ->options(collect(PaymentStatus::cases())->mapWithKeys(fn ($case) => [
                        $case->value => $case->getLabel(),
                    ])),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reservation.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reservation.user.name')
                    ->label('Reserved For')
                    ->sortable(),
                Tables\Columns\TextColumn::make('room_charges')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('extra_charges')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('taxes')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_status'),
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
                Tables\Actions\DeleteAction::make(),
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
            BillServicesRelationManager::class,
            PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBills::route('/'),
            'create' => Pages\CreateBill::route('/create'),
            'view' => Pages\ViewBill::route('/{record}'),
            'edit' => Pages\EditBill::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        if (! $user) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        if ($user->hasAnyRole([UserRoleType::HOTEL_CLERK->value, UserRoleType::HOTEL_MANAGER->value])) {
            $hotelIds = $user->userHotels()->pluck('hotel_id')->toArray();

            return parent::getEloquentQuery()->whereHas('reservation', function ($query) use ($hotelIds) {
                $query->whereIn('hotel_id', $hotelIds);
            });
        }

        if ($user->hasAnyRole([UserRoleType::TRAVEL_COMPANY->value, UserRoleType::CUSTOMER->value])) {
            return parent::getEloquentQuery()->whereHas('reservation', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        return parent::getEloquentQuery()->whereRaw('1 = 0');
    }
}
