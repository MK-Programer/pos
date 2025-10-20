<div>
    <x-filament::button 
        type="{{ $type ?? 'submit' }}" 
        class="{{ $class ?? 'mt-3' }}"
        wire:click="{{ $click ?? '' }}">
        {{ $label ?? 'Submit' }}
    </x-filament::button>

</div>
