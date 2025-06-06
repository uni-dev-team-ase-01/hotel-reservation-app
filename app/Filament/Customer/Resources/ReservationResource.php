<?php

namespace App\Filament\Customer\Resources;

use App\Enum\ReservationStatus;
use App\Enum\UserRoleType;
use App\Filament\Customer\Resources\ReservationResource\Pages;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'My Reservations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->numeric(),
                Forms\Components\DateTimePicker::make('check_in_date')
                    ->required(),
                Forms\Components\DateTimePicker::make('check_out_date')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('number_of_guests')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('cancellation_reason')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('cancellation_date'),
                Forms\Components\TextInput::make('confirmation_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('auto_cancelled')
                    ->required(),
                Forms\Components\Toggle::make('no_show_billed')
                    ->required(),
                Forms\Components\TextInput::make('hotel_id')
                    ->tel()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('confirmation_number')
                    ->label('Confirmation #')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable(),

                Tables\Columns\TextColumn::make('hotel.name')
                    ->label('Hotel')
                    ->searchable(),

                Tables\Columns\TextColumn::make('rooms_count')
                    ->label('Rooms')
                    ->counts('rooms')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('check_in_date')
                    ->label('Check-in')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('check_out_date')
                    ->label('Check-out')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('number_of_guests')
                    ->label('Guests')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => ReservationStatus::PENDING->value,
                        'success' => ReservationStatus::CONFIRMED->value,
                        'info' => ReservationStatus::CHECKED_IN->value,
                        'success' => ReservationStatus::CHECKED_OUT->value,
                        'danger' => ReservationStatus::CANCELLED->value,
                        'danger' => ReservationStatus::NO_SHOW->value,
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(ReservationStatus::cases())->mapWithKeys(fn ($case) => [
                        $case->value => $case->getLabel(),
                    ])),
            ])
            ->actions([
                Tables\Actions\Action::make('cancel')
                    ->label('Cancel Reservation')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Cancel Reservation')
                    ->modalDescription('Are you sure you want to cancel this reservation?')
                    ->action(function (Reservation $record) {
                        if (! in_array($record->status, [ReservationStatus::PENDING->value, ReservationStatus::CONFIRMED->value])) {
                            Notification::make()
                                ->title('Reservation cannot be cancelled')
                                ->body('Only pending or confirmed reservations can be cancelled.')
                                ->danger()
                                ->send();

                            return;
                        }

                        if ($record->check_in_date < now()) {
                            Notification::make()
                                ->title('Reservation cannot be cancelled')
                                ->body('Past reservations cannot be cancelled.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $record->update(['status' => ReservationStatus::CANCELLED->value, 'cancellation_date' => now()]);

                        Notification::make()
                            ->title('Reservation cancelled successfully')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Reservation $record) => $record->check_in_date > now()
                        &&
                        in_array(
                            $record->status,
                            [ReservationStatus::PENDING->value, ReservationStatus::CONFIRMED->value]
                        )),
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        if ($user->hasAnyRole([UserRoleType::CUSTOMER->value])) {
            $assignedReservationIds = $user->reservations->pluck('user_id');

            return parent::getEloquentQuery()
                ->whereIn('user_id', $assignedReservationIds);
        }

        return parent::getEloquentQuery()->whereRaw('1 = 0');
    }
}
