<div>
    <form wire:submit="save">
        {{ $this->form }}

        <x-filament::button type="submit" class="mt-3">
            Submit
        </x-filament::button>
    </form>

    <x-filament-actions::modals />
</div>
