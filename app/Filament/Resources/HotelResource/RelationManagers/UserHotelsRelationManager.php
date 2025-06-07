<?php

namespace App\Filament\Resources\HotelResource\RelationManagers;

use App\Enum\UserRoleType;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class UserHotelsRelationManager extends RelationManager
{
    protected static string $relationship = 'userHotels';

    protected static ?string $title = 'Hotel Users';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->options(User::all()->pluck('email', 'id'))
                    ->searchable()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.name')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.roles.name')
                    ->label('Role')
                    ->formatStateUsing(function ($state, $record) {
                        $roleName = $record->user->roles->first()?->name;

                        return $roleName ? UserRoleType::tryFrom($roleName)?->getLabel() ?? $roleName : 'No Role';
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.phone')
                    ->label('Phone')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Assigned On')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Assign User')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['hotel_id'] = $this->getOwnerRecord()->id;

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Remove Assignment'),
                // Tables\Actions\Action::make('view_user')
                //     ->label('View User')
                //     ->icon('heroicon-o-eye')
                //     ->url(fn($record) => UserResource::getUrl('view', ['record' => $record])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Remove Assignments'),
                ]),
            ]);
    }

    public function canCreate(): bool
    {
        $user = auth()->guard('admin')->user();

        if (! $user) {
            return false;
        }

        return $user->hasAnyRole([
            UserRoleType::SUPER_ADMIN->value,
        ]);
    }

    public function canEdit(Model $record): bool
    {
        return $this->canCreate();
    }

    public function canEditAny(): bool
    {
        return $this->canCreate();
    }

    public function canDelete(Model $record): bool
    {
        return $this->canCreate();
    }

    public function canDeleteBulk(): bool
    {
        return $this->canCreate();
    }

    public function canDeleteAny(): bool
    {
        return $this->canCreate();
    }
}
