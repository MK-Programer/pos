<?php

namespace App\Livewire\Widgets\Buttons;

class DeleteButton extends BaseButton
{
    public function __construct()
    {
        $this->name('delete')
            ->label('Delete')
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->successMessage('Deleted successfully')
            ->errorMessage('Error while deleting')
            ->requiresConfirmation()
            ->onAction(fn($record) => $record->delete());
    }
}
