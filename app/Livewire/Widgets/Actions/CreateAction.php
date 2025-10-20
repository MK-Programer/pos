<?php

namespace App\Livewire\Widgets\Actions;

use App\Enums\HandlerType;
use Filament\Actions\Action;

class CreateAction extends BaseAction
{
    public function __construct()
    {
        $this->setName('create')
             ->setLabel('Create');
    }

    public function handleRoute(string $routeName): Action
    {
        $this->handlerType = HandlerType::URL;

        $this->handler = fn() => route($routeName);

        return $this->toAction();
    }
}
