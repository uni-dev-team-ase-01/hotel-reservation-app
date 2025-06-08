<?php

namespace App\Filament\Customer\Resources\ReservationResource\RelationManagers;

use App\Enum\PaymentStatus;
use App\Filament\Customer\Resources\BillResource;
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
                Forms\Components\TextInput::make('reservation_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('reservation_id')
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
                    ->money('USD')
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
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('view_bill')
                    ->label('View Full Bill')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => BillResource::getUrl('view', ['record' => $record])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
