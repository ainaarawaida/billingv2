<?php

namespace App\Filament\App\Resources\PaymentMethodResource\Pages;

use App\Filament\App\Resources\PaymentMethodResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentMethod extends CreateRecord
{
    protected static string $resource = PaymentMethodResource::class;
}
