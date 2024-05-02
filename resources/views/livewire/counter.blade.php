<div>
    {{-- Be like water. --}}
    <h1>{{ $count }}</h1>

    {{ json_encode($bar) }}
 
 <button wire:click.prevent="increment">+</button>

 <button wire:click.prevent="decrement">-</button>
</div>
