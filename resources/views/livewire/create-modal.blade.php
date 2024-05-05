<div wire:ignore.self x-data="{ open: @entangle('showCreateModal') }" x-show="open">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
             
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="addItem">
                    <div class="form-group">
                        <div class="flex gap-4 items-center">
                            <label for="content" class="px-8">Content <span style="color:red;">*</span></label>
                            <x-filament::input.wrapper>
                                <textarea wire:model="content"
                                    id="content" name="content" rows="2"
                                    class="block w-full border-none bg-transparent px-3 py-1.5 text-base text-gray-950 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6">
                                    </textarea>
                                    @error('content')
                                        <div class="" style="color:red;">{{ $message }}</div>
                                    @enderror
                               
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
            <div class="modal-footer p-3">
             
             </div>
        </div>
    </div>
</div>