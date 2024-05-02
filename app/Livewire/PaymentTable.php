<?php

namespace App\Livewire;

use Filament\Tables;
use App\Models\Payment;
use Livewire\Component;
use App\Models\TeamSetting;
use Filament\Facades\Filament;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\App\Resources\PaymentResource;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;


class PaymentTable extends Component 
{
  
    public $record;
    public $showCreateModal = false;
    public $items = [];
    public $newItem = '';
    public $type ;
    public $payment_method_id ;
    public $payment_date ;
    public $total ;
    public $notes ;
    public $status ;

    public function mount()
    {
       $this->items = Payment::query()
       ->where('team_id', Filament::getTenant()->id)
       ->where('invoice_id', $this->record->id)
       ->get();

    }

    public function delete($id)
    {
        Payment::destroy($id);
        $this->items = Payment::where('team_id', Filament::getTenant()->id)
        ->where('invoice_id', $this->record->id)->get();

    }

    public function addItem()
    {
        $data['team_id'] = Filament::getTenant()->id ;
        $data['invoice_id'] = $this->record->id ;
        $data['payment_method_id'] = $this->payment_method_id ;
        $data['payment_date'] = $this->payment_date ;
        $data['total'] = $this->total ;
        $data['notes'] = $this->notes ;
        $data['status'] = $this->status ;

        $payment = Payment::create($data);
        $this->items = Payment::where('team_id', Filament::getTenant()->id)
        ->where('invoice_id', $this->record->id)->get();

        $this->showCreateModal = false;
        $this->newItem = '';
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->newItem = ''; // Clear new item value on close
    }

    public function openCreateModal()
    {
        $this->showCreateModal = true;
        // $this->dispatch('open-modal', id: 'edit-note');
    }

    public function render()
    {
        return view('livewire.payment-table');
    }
}
