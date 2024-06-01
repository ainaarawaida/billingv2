<?php

namespace App\Filament\Client\Pages\Auth;

use Exception;
use App\Models\Team;
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
use Filament\Forms\Components\Select;
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
use DominionSolutions\FilamentCaptcha\Forms\Components\Captcha;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Illuminate\Support\Facades\Session;

/**
 * @property Form $form
 */
class Register extends oriRegister
{

    public ?array $data = [];

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        $this->callHook('beforeFill');


        $current_company = Session::get('current_company');
        $this->data['team_id'] = $current_company;

        $this->form->fill([
            'team_id' => $this->data['team_id'] ?? '',
        ]);

        $this->callHook('afterFill');
    }
  
    protected function handleRegistration(array $data): Model
    {
        $user = $this->getUserModel()::create($data);
        $role = Role::where('name', 'customer')->get() ;
        $user->assignRole($role);
        $team = Team::where('id', $data['team_id'])->first();
        $team->members()->attach($user);

        return $user;
    }



    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        Select::make('team_id')
                            ->label('Company Name')
                            ->options(Team::where('id', $this->data['team_id'])->first()?->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->disabled()
                            ->dehydrated(true)
                            ->default($this->data['team_id']),
                            
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        Captcha::make('captcha')
                            ->rules(['captcha'])
                            ->required()
                            ->validationMessages([
                                'captcha'  =>  __('Captcha does not match the image'),
                            ]),
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
