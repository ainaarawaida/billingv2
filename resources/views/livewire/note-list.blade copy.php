

<div>

<div class="flex justify-end p-3">
<x-filament::button wire:click.prevent="openCreateModal">
    Add Note
</x-filament::button>
</div>


    @if ($showCreateModal)
        @include('livewire.create-modal')
    @endif

<table class="table-auto w-full shadow-md">
  <thead>
    <tr class="bg-gray-100 text-gray-600 text-left">
      <th class="px-3 py-2">Name</th>
      <th class="px-3 py-2">Content</th>
      <th class="px-3 py-2">Action</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($items as $item)
      <tr class="border hover:bg-gray-100">
        <td class="px-3 py-2">{{ $item->user->name  }}</td>
        <td class="px-3 py-2">{{ $item->content }}</td>
        <td class="px-3 py-2">
        <x-filament::button wire:click.prevent="delete({{ $item->id }})">
            Delete
        </x-filament::button>

        </td>
      </tr>
    @endforeach
  </tbody>
</table>




</div>
