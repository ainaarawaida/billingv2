<?php

namespace App\Http\Responses\Auth;

use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        $url = explode('/', Filament::getUrl());
        if($url[3] == 'app'){
            return redirect()->intended(Filament::getUrl().'/choose-company');
        }else{
            return redirect()->intended(Filament::getUrl());

        }
      
    }
}