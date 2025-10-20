<div>
    <form wire:submit="create">
        {{ $this->form }}

        <livewire:widgets.actions.submit-action/>
    </form>

    <x-filament-actions::modals />
</div>
