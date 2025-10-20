<?php

namespace App\Livewire\Widgets\Actions;

use Filament\Actions\Action;

class DeleteAction extends BaseAction
{
    public function __construct()
    {
        $this->setName('delete')
             ->setLabel('Delete')
             ->setColor('danger')
             ->confirmBeforeAction()
             ->success('Deleted successfully')
             ->error('Deletion error');
    }

    public static function make()
    {
        return parent::make()->handleAction(fn($record) => $record->delete());
    }
}
