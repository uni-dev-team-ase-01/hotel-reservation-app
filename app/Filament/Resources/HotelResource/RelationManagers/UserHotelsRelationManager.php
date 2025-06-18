<?php

namespace App\Filament\Resources\HotelResource\RelationManagers;

use App\Enum\UserRoleType;
use App\Models\User;
use App\Services\UserService;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
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
        $user = auth()->user();

        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Customer')
                    ->hidden(fn() => $user->hasAnyRole([UserRoleType::TRAVEL_COMPANY->value]))
                    ->relationship('user', 'name', function (Builder $query) {
                        $query->whereHas('roles', function (Builder $roleQuery) {
                            $roleQuery->whereIn('name', [
                                UserRoleType::HOTEL_CLERK->value,
                                UserRoleType::HOTEL_MANAGER->value,
                            ]);
                        });
                    })
                    ->searchable()
                    ->preload()
                    ->default(function () {
                        $user_id = request()->query('user_id');
                        if (
                            $user_id &&
                            User::where('id', $user_id)
                            ->whereHas('roles', function (Builder $roleQuery) {
                                $roleQuery->whereIn('name', [
                                    UserRoleType::HOTEL_CLERK->value,
                                    UserRoleType::HOTEL_MANAGER->value,
                                ]);
                            })
                            ->exists()
                        ) {
                            return $user_id;
                        }

                        return null;
                    })
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(User::class, 'email'),

                        Forms\Components\Select::make('role')
                            ->options(
                                \Spatie\Permission\Models\Role::whereIn('name', [
                                    UserRoleType::HOTEL_CLERK->value,
                                    UserRoleType::HOTEL_MANAGER->value,
                                ])
                                    ->pluck('name', 'name')
                            )
                            ->required()
                            ->preload(),

                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Placeholder::make('password_info')
                            ->label('Password')
                            ->content('A secure password will be automatically generated and sent via email'),

                        Forms\Components\Toggle::make('send_welcome_email')
                            ->label('Send welcome email with login credentials')
                            ->default(true)
                            ->helperText('User will receive an email with their login details'),
                    ])
                    ->createOptionUsing(function (array $data) {
                        $userService = new UserService();
                        $user = $userService->createUser($data, $data['role']);
                        return $user->id;
                    })
                    ->createOptionAction(function (Action $action) {
                        return $action
                            ->modalHeading('Create New User')
                            ->modalSubmitActionLabel('Create User')
                            ->modalWidth('lg');
                    })
                    ->required(!$user->hasRole(UserRoleType::HOTEL_CLERK->value)),
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
