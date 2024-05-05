<?php

use App\Models\TeamSetting;
use Filament\Facades\Filament;
use App\Filament\App\Resources\PaymentResource;
    $paymenturl = PaymentResource::getUrl() ;
    $prefix = TeamSetting::where('team_id', Filament::getTenant()->id )->first()->invoice_prefix_code ?? '#I' ;

?>



<div>

    <div class="flex justify-end p-3">
        <x-filament::button wire:click.prevent="openCreateModal">
            Add Payment
        </x-filament::button>
    </div>

    @if ($showCreateModal)
        
        <div wire:ignore.self x-data="{ open: @entangle('showCreateModal') }" x-show="open">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="addItem">
                            <div class="form-group">

                            {{ $this->form }}

                                <div class="flex flex-row gap-4 items-center pt-2">

                                    <x-filament::button type="submit" class="px-8">
                                        Add
                                    </x-filament::button>
                                    <x-filament::button wire:click.prevent="closeCreateModal" class="px-8">
                                        Close
                                    </x-filament::button>
                                
                                </div>
                            
                            </div>
                            
                        </form>
                    </div>
                    <div class="modal-footer p-3">
                    
                    </div>
                </div>
            </div>
        </div>

    @endif

    <table class="table-auto w-full shadow-md">
    <thead>
        <tr class="bg-gray-100 text-gray-600 text-left">
        <th class="px-3 py-2">invoice No</th>
        <th class="px-3 py-2">Payment Method</th>
        <th class="px-3 py-2">Total</th>
        <th class="px-3 py-2">status</th>
        <th class="px-3 py-2">action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
        <tr class="border hover:bg-gray-100">
            <td class="px-3 py-2">{{ $prefix.$item->invoice->numbering  }}</td>
            <td class="px-3 py-2">{{ $item->payment_method->name }}</td>
            <td class="px-3 py-2">{{ number_format($item->total, 2)  }}</td>
            <td class="px-3 py-2">
                <x-filament::badge>
                    {{ ucwords(str_replace("_", " ", $item->status)) }}
                </x-filament::badge>
            </td>
            <td class="px-3 py-2">
            <x-filament::button wire:click.prevent="delete({{ $item->id }})">
                Delete
            </x-filament::button>
            <x-filament::button     
                href="{{ $paymenturl }}/{{ $item->id }}/edit"
                tag="a">
                View
            </x-filament::button>

            </td>
        </tr>
        @endforeach
    </tbody>
    </table>

   
</div>
