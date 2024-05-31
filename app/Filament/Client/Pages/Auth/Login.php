<?php

namespace App\Filament\Client\Pages\Auth;

use App\Models\Team;

use App\Models\User;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Actions\ActionGroup;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Session;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\RichEditor;
use Filament\Pages\Auth\Login as BaseAuth;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Validation\ValidationException;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Illuminate\Contracts\View\View;

class Login extends BaseAuth
{
    /**
     * Get the form for the resource.
     */
    public ?array $data = [];

    public function mount(): void
    {
        // dd($this->data['team_id']);
        if(request()->query('company')){
            $this->data['team_id'] = Team::where('slug', request()->query('company'))->first()->id ?? '';
            Session::put('current_company', $this->data['team_id']);
        }else{
            $current_company = Session::get('current_company');
            $this->data['team_id'] = $current_company;
        }

        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        $this->form->fill([
            'team_id' => $this->data['team_id'] ?? '',
            'username' => 'admin@admin.test',
            'password' => 'admin'
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('team_id')
                ->label('Company Name')
                ->options(Team::where('id', $this->data['team_id'])->first()?->pluck('name', 'id'))
                ->searchable()
                ->disabled()
                ->dehydrated(true)
                ->default($this->data['team_id']),
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
        $data = $this->form->getState();
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/login.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/login.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/login.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

         //this is manual input user data login
        
         $finduser = Team::with('members')->where('id', $data['team_id'])
         ->first()->members()->where('name', $data['username'])
         ->orWhere('email', $data['username'])->first();

        if($finduser && Hash::check($data['password'], $finduser->password)){
            //Initiate Laravel Authentication: Manually log in the user using Auth::login($user).
            Auth::login($finduser);
            $user = Filament::auth()->user();
        }else{
            throw ValidationException::withMessages([
                'data.username' => __('filament-panels::pages/auth/login.messages.failed'),
            ]);
   
           if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
               $this->throwFailureValidationException();
           }

         

       

       }
       $user = Filament::auth()->user();

        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }




    public function hasLogo(): bool
    {
        return false;
    }

    
    protected function getFormActions(): array
    {
        $url = explode('/', url()->current());
        if($url[3] == 'client'){
            $url[3] = 'companys';
        }
        return [
            Action::make('Back')
            ->url(url($url[3]))
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
