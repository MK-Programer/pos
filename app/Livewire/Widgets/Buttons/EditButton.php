<?php

namespace App\Livewire\Widgets\Buttons;

class EditButton extends BaseButton
{
    public function __construct()
    {
        $this->name('edit')
            ->label('Edit')
            // ->icon('heroicon-o-pencil')
            ->toRoute(fn($record): string => route('item.edit', ['record' => $record]))
            ->openUrlInNewTab();

    }
}
