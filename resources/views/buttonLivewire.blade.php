
<?php

// dd($getRecord());
?>
<a href="{{  url('quotationpdf').'/'.$getRecord()->id }}" wire:navigate>
<button style="--c-50:var(--danger-50);--c-400:var(--danger-400);" class="fi-dropdown-list-item flex w-full items-center gap-2 whitespace-nowrap rounded-md p-2 text-sm transition-colors duration-75 outline-none disabled:pointer-events-none disabled:opacity-70 fi-color-custom fi-dropdown-list-item-color-danger hover:bg-custom-50 focus-visible:bg-custom-50 dark:hover:bg-custom-400/10 dark:focus-visible:bg-custom-400/10 fi-ac-action fi-ac-grouped-action" type="button" wire:loading.attr="disabled" >
                    <!--[if BLOCK]><![endif]-->    
        
        <span class="fi-dropdown-list-item-label flex-1 truncate text-start text-custom-600 dark:text-custom-400 " style="--c-400:var(--danger-400);--c-600:var(--danger-600);">
            Click
        </span>

</button>
</a>