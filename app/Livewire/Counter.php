<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;

class Counter extends Component
{
    public $count = 1;
    public $data = [];
    public $bar = "abu";
    public ?Model $record = null;

    public function mount(string $bar): void
    {  
        
    }
 
    public function increment()
    {
        $this->count++;
    }
 
    public function decrement()
    {
        $this->count--;
    }
 
    public function render()
    {
        return view('livewire.counter');
    }
}
