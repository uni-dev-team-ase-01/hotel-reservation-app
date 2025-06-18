<?php

namespace App\Services;

use App\Enum\UserRoleType;
use App\Mail\UserCreated;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserService
{
    public function createUser(array $data, $userRole)
    {
        $generatedPassword = Str::random(12);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => bcrypt($generatedPassword),
            'email_verified_at' => now(),
        ]);

        if ($userRole === UserRoleType::CUSTOMER->value) {
            $user->guard_name = 'web';
            $user->assignRole(UserRoleType::CUSTOMER->value);
        } else {
            $user->guard_name = 'admin';
            $user->assignRole($userRole);
        }

        if ($data['send_welcome_email'] ?? true) {
            try {
                $userRole = UserRoleType::from($userRole);
                $roleLabel = $userRole->getLabel();

                Mail::to($user->email)->send(new UserCreated($user, $generatedPassword, $roleLabel));

                Notification::make()
                    ->title('User created successfully')
                    ->body('Welcome email sent to ' . $user->email)
                    ->success()
                    ->send();
            } catch (\Exception $e) {
                logger('Failed to send welcome email', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $e->getMessage(),
                ]);
                Notification::make()
                    ->title('User created but email failed')
                    ->body('User created successfully, but welcome email could not be sent.')
                    ->warning()
                    ->send();
            }
        } else {
            Notification::make()
                ->title('User created successfully')
                ->body('Password: ' . $generatedPassword . ' (Please save this!)')
                ->success()
                ->persistent()
                ->send();
        }

        return $user;
    }
}
