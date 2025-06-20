<?php

namespace App\Filament\Resources\BillResource\RelationManagers;

use App\Models\HotelService;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class BillServicesRelationManager extends RelationManager
{
    protected static string $relationship = 'billServices';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('service_id')
                    ->label('Service')
                    ->options(function () {
                        $hotelId = $this->getOwnerRecord()->reservation->hotel_id;
                        return Service::whereHas('hotels', function ($query) use ($hotelId) {
                            $query->where('hotel_id', $hotelId);
                        })->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        if ($state) {
                            $service = HotelService::where('services_id', $state)
                                ->where('hotel_id', $this->getOwnerRecord()->reservation->hotel_id)
                                ->first();
                            $set('charge', $service ? $service->charge : null);
                        } else {
                            $set('charge', null);
                        }
                    }),

                Forms\Components\TextInput::make('charge')
                    ->label('Service Charge')
                    ->numeric()
                    ->step(0.01)
                    ->prefix('$')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('service.name')
            ->columns([
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('charge')
                    ->label('Charge')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Added At')
                    ->dateTime()
                    ->sortable(),
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
