<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        @livewire('dashboard.application-stats')

        <div>
            @livewire('dashboard.latest-sales')
        </div>

    </div>
</x-layouts.app>
