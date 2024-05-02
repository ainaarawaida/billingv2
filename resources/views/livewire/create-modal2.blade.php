<x-filament::modal id="edit-note">
<x-slot name="heading">
        Modal heading
    </x-slot>
 
    <x-slot name="description">
        Modal description
    </x-slot>
 
            <div class="modal-body">
                <form wire:submit.prevent="addItem">
                    <div class="form-group">
                        <div class="flex gap-4 items-center">
                            <label for="newItem" class="px-8">Item Name: </label>
                            <x-filament::input.wrapper>
                                <x-filament::input
                                    type="text"
                                    wire:model="newItem"
                                    id="newItem" name="newItem"
                                />
                            </x-filament::input.wrapper>
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

    
            <x-slot name="footerActions">
                {{-- Modal footer actions --}}
            </x-slot>
</x-filament::modal>