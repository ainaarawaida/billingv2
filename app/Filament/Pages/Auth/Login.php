<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Pages\Auth\Login as BaseAuth;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\ValidationException;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;

class Login extends BaseAuth
{
    /**
     * Get the form for the resource.
     */

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getUsernameFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    /**
     * Get the username form component.
     */
    protected function getUsernameFormComponent(): Component
    {
        return TextInput::make('username')
            ->label('Username')
            ->required()
            ->autocomplete()
            ->autofocus()
            ->default('admin@admin.test')
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getPasswordFormComponent(): Component
    {
        // parent::getPasswordFormComponent();
        return TextInput::make('password')
            ->label(__('filament-panels::pages/auth/login.form.password.label'))
            ->hint(filament()->hasPasswordReset() ? new HtmlString(Blade::render('<x-filament::link :href="filament()->getRequestPasswordResetUrl()"> {{ __(\'filament-panels::pages/auth/login.actions.request_password_reset.label\') }}</x-filament::link>')) : null)
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->autocomplete('current-password')
            ->required()
            ->default('admin')
            ->extraInputAttributes(['tabindex' => 2]);
    }

    /**
     * Get the credentials from the form data.
     */
    protected function getCredentialsFromFormData(array $data): array
    {
        $type = filter_var($data['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        return [
            $type => $data['username'],
            'password' => $data['password'],
        ];
    }

    /**
     * Authenticate the user.
     */
    public function authenticate(): ?LoginResponse
    {
        try {
            return parent::authenticate();
        } catch (ValidationException) {
            throw ValidationException::withMessages([
                'data.username' => __('filament-panels::pages/auth/login.messages.failed'),
            ]);
        }
    }




    public function hasLogo(): bool
    {
        return false;
    }

    
    protected function getFormActions(): array
    {
        return [
           
            Action::make('Back')
            ->url('/')
            ->extraAttributes(['style' => 'width:30%;','class' => 'bg-gray-400']),    
            $this->getAuthenticateFormAction()
            ->extraAttributes(['style' => 'width:60%;']),   
        ];
    }

    protected function hasFullWidthFormActions(): bool
    {
        return false;
    }
}
