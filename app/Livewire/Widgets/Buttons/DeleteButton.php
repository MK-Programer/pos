<?php

namespace App\Livewire\Widgets\Buttons;

class DeleteButton extends BaseButton
{
    public function __construct()
    {
        $this->name('delete')
            ->label('Delete')
            // ->icon('heroicon-o-trash')
            ->color('danger')
            ->successTitleMessage('Deleted successfully')
            ->errorTitleMessage('Deletion error')
            ->requiresConfirmation()
            ->onAction(fn($record) => $record->delete());
    }
}
