<?php

namespace App\Filament\Pages\Auth;
use DominionSolutions\FilamentCaptcha\Forms\Components\Captcha;
use Filament\Pages\Auth\PasswordReset\RequestPasswordReset as oriRequestPasswordReset;

/**
 * @property Form $form
 */
class RequestPasswordReset extends oriRequestPasswordReset
{
    /**
     * @return array<int | string, string | Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent(),
                        Captcha::make('captcha')
                            ->rules(['captcha'])
                            ->required()
                            ->dehydrated(false)
                            ->validationMessages([
                                'captcha'  =>  __('Captcha does not match the image'),
                            ]),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

}
