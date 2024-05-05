<?php

namespace App\Livewire;

use Filament\Forms;
use Filament\Tables;
use App\Models\Payment;
use Filament\Forms\Get;
use Livewire\Component;
use Filament\Forms\Form;
use App\Models\TeamSetting;
use Livewire\Attributes\On;
use App\Models\PaymentMethod;
use Filament\Facades\Filament;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\App\Resources\PaymentResource;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;


class PaymentTable extends Component implements HasForms 
{

    use InteractsWithForms;
  
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

    public ?array $paymentData = [];
    public ?array $openPaymentForm = [];

    protected function getForms(): array
    {
        return [
            'form',
            'paymentForm',
        ];
    }

    public function paymentForm(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\Select::make('payment_method_id')
                        ->label("Payment Method")
                        ->options(function (Get $get, string $operation){
                            $payment_method = PaymentMethod::where('team_id', Filament::getTenant()->id)
                            ->where('status', 1)->get()->pluck('name', 'id');
                            return $payment_method ;
                        })
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\DatePicker::make('payment_date')
                        ->required()
                        ->default(now()),
                    Forms\Components\TextInput::make('total')
                        ->required()
                        ->prefix('RM')
                        ->regex('/^[0-9]*(?:\.[0-9]*)?(?:,[0-9]*(?:\.[0-9]*)?)*$/')
                        ->formatStateUsing(fn (string $state): string => number_format($state, 2))
                        ->dehydrateStateUsing(fn (string $state): string => (float)str_replace(",", "", $state))
                        ->default(0.00),
                    Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'pending_payment' => 'Pending payment',
                                'on_hold' => 'On hold',
                                'processing ' => 'Processing ',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                                'canceled' => 'Canceled',
                                'refunded' => 'Refunded',
                            ])
                            ->default('draft')
                            ->searchable()
                            ->preload()
                            ->required(),
                    Forms\Components\Textarea::make('notes')
                        ->maxLength(65535)
                        ->columnSpanFull(),

                    
                ])
                ->columns(2),
        ])
        ->statePath('paymentData')
        ->model(Payment::class);
    }

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\Actions::make([
                        Forms\Components\Actions\Action::make('Add Payment')
                            ->label(__('Add Payment'))
                            ->action(function (array $data, $record): void {
                                $this->showCreateModal = true;
                                $this->dispatch('open-modal', id: 'payment-form');
                                
                            })
                    ]),
                    
                ])
                ->columns(2),
        ])
        ->statePath('openPaymentForm')
        ->model(Payment::class);
    }

    // public function validate($rules = null, $messages = [], $attributes = []){
     
    //     dd("ff",$this->form->getState());
    // }

 
    

    #[On('refreshPaymentTable')] 
    public function mount()
    {
       $this->items = Payment::query()
       ->where('team_id', Filament::getTenant()->id)
       ->where('invoice_id', $this->record->id)
       ->get();

       $this->form->fill();
       $this->paymentForm->fill();

    }


    public function delete($id)
    {
        Payment::destroy($id);
        $payment = Payment::where('team_id', Filament::getTenant()->id)
        ->where('invoice_id', $this->record->id) ;

        $this->items = $payment->get();

         //update balance on invoice
         $totalPayment = $payment
         ->where('status', 'completed')->sum('total');

         $this->record->balance = $this->record->final_amount - $totalPayment; 
         $this->record->update();

    }

    public function render()
    {
        return view('livewire.payment-table');
    }
}
