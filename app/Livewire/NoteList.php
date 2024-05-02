<?php

namespace App\Livewire;

use App\Models\Note;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Action;

class NoteList extends Component   
{
    public $record ;
    public $showCreateModal = false;
    public $items = [];
    public $newItem = '';
    public $type ;
 
    public function mount()
    {
      
       $this->items = Note::where('type_id', $this->record->id)->where('type', $this->type)
        ->where('team_id', Filament::getTenant()->id)->get();
    }

    public function render()
    {
        return view('livewire.note-list');
    }

    public function delete($id)
    {
        Note::destroy($id);
        $this->items = Note::where('type_id', $this->record->id)->where('type', $this->type)
        ->where('team_id', Filament::getTenant()->id)->get();

    }

    public function addItem()
    {
        $data['user_id'] = auth()->user()->id ;
        $data['team_id'] = Filament::getTenant()->id ;
        $data['type_id'] = $this->record->id ;
        $data['type'] = $this->type ;
        $data['content'] = $this->newItem ;
        $note = Note::create($data);
        $this->items = Note::where('type_id', $this->record->id)->where('type', $this->type)
        ->where('team_id', Filament::getTenant()->id)->get();

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
}
