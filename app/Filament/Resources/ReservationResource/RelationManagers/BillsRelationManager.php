<?php

namespace App\Filament\Resources\ReservationResource\RelationManagers;

use App\Enum\PaymentStatus;
use App\Filament\Resources\BillResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class BillsRelationManager extends RelationManager
{
    protected static string $relationship = 'bills';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('room_charges')
                    ->required()
                    ->numeric()
                    ->label('Room Charges')
                    ->step(0.01)
                    ->prefix('$'),

                Forms\Components\TextInput::make('extra_charges')
                    ->numeric()
                    ->label('Extra Charges')
                    ->step(0.01)
                    ->prefix('$')
                    ->nullable(),

                Forms\Components\TextInput::make('discount')
                    ->numeric()
                    ->label('Discount')
                    ->step(0.01)
                    ->suffix('%')
                    ->nullable(),

                Forms\Components\TextInput::make('taxes')
                    ->required()
                    ->numeric()
                    ->label('Taxes')
                    ->step(0.01)
                    ->prefix('$'),

                Forms\Components\TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->label('Total Amount')
                    ->step(0.01)
                    ->prefix('$')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $roomCharges = (float) $get('room_charges') ?? 0;
                        $extraCharges = (float) $get('extra_charges') ?? 0;
                        $discount = (float) $get('discount') ?? 0;
                        $taxes = (float) $get('taxes') ?? 0;

                        $calculated = ($roomCharges + $extraCharges - $discount) + $taxes;
                        $set('total_amount', $calculated);
                    }),
                Forms\Components\Select::make('payment_status')
                    ->required()
                    ->options(collect(PaymentStatus::cases())->mapWithKeys(fn ($case) => [
                        $case->value => $case->getLabel(),
                    ]))
                    ->default(PaymentStatus::PENDING->value)
                    ->label('Payment Status'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Bill ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('room_charges')
                    ->money('USD')
                    ->sortable()
                    ->label('Room Charges'),

                Tables\Columns\TextColumn::make('extra_charges')
                    ->money('USD')
                    ->sortable()
                    ->label('Extra Charges')
                    ->placeholder('None'),

                Tables\Columns\TextColumn::make('discount')
                    ->suffix('%')
                    ->sortable()
                    ->label('Discount')
                    ->placeholder('None'),

                Tables\Columns\TextColumn::make('taxes')
                    ->money('USD')
                    ->sortable()
                    ->label('Taxes'),

                Tables\Columns\TextColumn::make('total_amount')
                    ->money('USD')
                    ->sortable()
                    ->weight('bold')
                    ->label('Total Amount'),

                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->colors([
                        'warning' => PaymentStatus::PENDING->value,
                        'success' => PaymentStatus::PAID->value,
                        'danger' => PaymentStatus::CANCELLED->value,
                    ])
                    ->label('Payment Status'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Created'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Updated'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options(collect(PaymentStatus::cases())->mapWithKeys(fn ($case) => [
                        $case->value => $case->getLabel(),
                    ]))
                    ->label('Payment Status'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        if (! isset($data['total_amount']) || $data['total_amount'] == 0) {
                            $roomCharges = (float) $data['room_charges'] ?? 0;
                            $extraCharges = (float) $data['extra_charges'] ?? 0;
                            $discount = (float) $data['discount'] ?? 0;
                            $taxes = (float) $data['taxes'] ?? 0;

                            $data['total_amount'] = ($roomCharges + $extraCharges - $discount) + $taxes;
                        }

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('view_bill')
                    ->label('View Full Bill')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => BillResource::getUrl('view', ['record' => $record])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}
