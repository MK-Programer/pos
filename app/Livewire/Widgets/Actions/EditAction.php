<?php

namespace App\Livewire\Widgets\Actions;

class EditAction extends BaseAction
{
    public function __construct()
    {
        $this->setName('edit')
             ->setLabel('Edit')
             ->openInNewTab();
    }
}
