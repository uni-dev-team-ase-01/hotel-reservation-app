<?php

namespace App\Filament\Resources;

use App\Enum\UserRoleType;
use App\Filament\Resources\OccupancyPredictionResource\Pages;
use App\Filament\Resources\OccupancyPredictionResource\RelationManagers;
use App\Models\Hotel;
use App\Models\OccupancyPrediction;
use App\Services\OccupancyPredictionService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OccupancyPredictionResource extends Resource
{
    protected static ?string $model = OccupancyPrediction::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Occupancy Predictions';
    protected static ?string $navigationGroup = 'Reports';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = auth()->user();
        $isHotelStaff = $user->hasAnyRole([UserRoleType::HOTEL_CLERK->value, UserRoleType::HOTEL_MANAGER->value]);
        $userHotels = $isHotelStaff ? $user->userHotels->pluck('hotel_id') : collect();

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('hotel.name')
                    ->label('Hotel')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('predicted_occupancy')
                    ->label('Predicted %')
                    ->suffix('%')
                    ->color(fn($state) => $state > 80 ? 'success' : ($state > 60 ? 'warning' : 'danger'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_occupancy')
                    ->label('Actual %')
                    ->suffix('%')
                    ->color(fn($state) => $state > 80 ? 'success' : ($state > 60 ? 'warning' : 'danger'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('confidence_score')
                    ->label('Confidence')
                    ->suffix('%')
                    ->color(fn($state) => $state > 80 ? 'success' : ($state > 60 ? 'warning' : 'danger'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('prediction_method')
                    ->label('Method')
                    ->badge()
                    ->colors([
                        'primary' => 'weighted_multi_factor',
                        'secondary' => 'manual',
                        'success' => 'linear_regression',
                        'warning' => 'seasonal_decomposition'
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from_date'),
                        Forms\Components\DatePicker::make('to_date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['to_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),
                Tables\Filters\Filter::make('future_predictions')
                    ->query(fn(Builder $query): Builder => $query->where('date', '>=', now()))
                    ->label('Future Predictions Only'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('generate_predictions')
                    ->label('Generate Next Week Predictions')
                    ->icon('heroicon-o-sparkles')
                    ->color('success')
                    ->form([
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
                    ])
                    ->action(function (array $data) {
                        try {
                            $predictionService = app(OccupancyPredictionService::class);
                            $predictions = $predictionService->predictNextWeekOccupancy($data['hotel_id']);
                            $predictionService->savePredictions($predictions);

                            Notification::make()
                                ->title('Predictions Generated Successfully')
                                ->body("Generated {$predictions->count()} predictions for next week")
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Prediction Generation Failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOccupancyPredictions::route('/'),
        ];
    }
}
