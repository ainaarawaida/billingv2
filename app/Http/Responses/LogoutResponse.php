<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LogoutResponse as Responsable;
use Illuminate\Http\RedirectResponse;


class LogoutResponse implements Responsable
{
    public function toResponse($request): RedirectResponse
    {
        $previousUrl = url()->previous();
        $url = explode('/', $previousUrl);
        if($url[3] == 'client'){
            return redirect()->intended(url('client/login?company='.$url[4]));
        }
    
        // change this to your desired route
        return redirect()->route('login');
    }
}