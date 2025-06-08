<?php

namespace App\Filament\Resources;

use App\Enum\PaymentMethod;
use App\Enum\UserRoleType;
use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationGroup = 'Financial';

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('bill_id')
                    ->relationship('bill', 'id')
                    ->required(),
                Forms\Components\Select::make('method')
                    ->required()
                    ->options(collect(PaymentMethod::cases())->mapWithKeys(fn ($case) => [
                        $case->value => $case->getLabel(),
                    ])),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                // Forms\Components\DateTimePicker::make('paid_at')
                //     ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bill.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('method'),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'view' => Pages\ViewPayment::route('/{record}'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
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

            return parent::getEloquentQuery()->whereHas('bill.reservation', function ($query) use ($hotelIds) {
                $query->whereIn('hotel_id', $hotelIds);
            });
        }

        if ($user->hasAnyRole([UserRoleType::TRAVEL_COMPANY->value, UserRoleType::CUSTOMER->value])) {
            return parent::getEloquentQuery()->whereHas('bill.reservation', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        return parent::getEloquentQuery()->whereRaw('1 = 0');
    }
}
