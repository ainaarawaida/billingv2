<?php

namespace App\Filament\Pages\Auth;

use Exception;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Pages\SimplePage;
use Filament\Actions\ActionGroup;
use Illuminate\Auth\SessionGuard;
use Spatie\Permission\Models\Role;
use Filament\Events\Auth\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Notifications\Auth\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Pages\Auth\Register as oriRegister;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

/**
 * @property Form $form
 */
class Register extends oriRegister
{
    protected function handleRegistration(array $data): Model
    {
        $user = $this->getUserModel()::create($data);
        $role = Role::where('name', 'admin')->get() ;
        $user->assignRole($role);
        return $user;
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label(__('filament-panels::pages/auth/register.form.name.label'))
            ->unique(table: User::class, column: 'name')
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

}
