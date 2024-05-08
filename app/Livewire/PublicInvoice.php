<?php

namespace App\Livewire;

use Filament\Forms;
use App\Models\Item;
use App\Models\Team;
use App\Models\Invoice;
use Livewire\Component;
use App\Models\Customer;
use Filament\Forms\Form;
use App\Models\TeamSetting;
use Filament\Actions\Action;
use Livewire\Attributes\Layout;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

class PublicInvoice extends Component implements HasForms, HasActions
{

    use InteractsWithActions;
    use InteractsWithForms;
    
    public ?array $data = [];
    public $id ;
    // public $record ;
    // public $team ;
    // public $item ;
    // public $customer ;
    // public $prefix ;
    

    
    public function mount($id = null): void
    {
        // $this->id = str_replace('luqmanahmadnordin', "", base64_decode($id)) ;
        // $this->record = Invoice::where('id',$this->id)->first();
        // $this->team = Team::where('id', $this->record->team_id)->first();
        // $this->item = Item::with('product')->where('invoice_id', $this->record->id)->get();
        // $this->customer = Customer::where('id', $this->record->customer_id)->first();
        // $this->prefix = TeamSetting::where('team_id', $this->record->team_id )->first()->invoice_prefix_code ?? '#Q' ;
  
        $this->form->fill();
    }

    public function testAction(): Action
    {
        return Action::make('delete')
            ->color('primary')
            ->extraAttributes(['class' => 'bg-red-500 m-1'])
            ->requiresConfirmation()
            ->action(fn () => $this->post->delete());
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                    Actions::make([
                        Actions\Action::make('Generate excerpt')
                            ->button()
                            ->action(function () {
                            })
                            ->label('More actions')
                            ->icon('heroicon-m-ellipsis-vertical')
                            ->color('primary')
                            ->button()
                    ])
                   
                    ->extraAttributes([]),
                    Actions::make([
                        Actions\Action::make('Generate excerpt')
                            ->action(function () {
                            })
                    ])
                    ->extraAttributes(['class' => 'grow  bg-slate-500']),
                    Actions::make([
                        Actions\Action::make('Generate excerpt')
                            ->action(function () {
                            })
                    ])
                    ->extraAttributes(['class' => 'grow  bg-slate-500']),
                    Actions::make([
                        Actions\Action::make('Generate excerpt')
                            ->action(function () {
                            })
                    ])
                    ->extraAttributes(['class' => 'grow  bg-slate-500']),
                    Actions::make([
                        Actions\Action::make('Generate excerpt')
                            ->action(function () {
                            })
                    ])
                    ->extraAttributes(['class' => 'grow  bg-slate-500']),
                    Actions::make([
                        Actions\Action::make('Generate excerpt')
                            ->action(function () {
                            })
                    ])
                    ->extraAttributes(['class' => 'grow  bg-slate-500']),
                   
                   
                ])
                ->columns([
                    'default' => 2,
                ])
                ->extraAttributes(['class' => '']),
            ])
           
            ->statePath('data');
    }
    
    public function create(): void
    {
        dd($this->form->getState());
    }


    #[Layout('components.layouts.public')] 
    public function render()
    {
        return view('livewire.public-invoice');
    }
}
