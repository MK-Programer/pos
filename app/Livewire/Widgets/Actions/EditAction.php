<?php

namespace App\Livewire\Widgets\Actions;

use App\Enums\HandlerType;
use Filament\Actions\Action;

class EditAction extends BaseAction
{
    public function __construct()
    {
        $this->setName('edit')
             ->setLabel('Edit')
             ->openInNewTab();
    }

    public function handleRoute(string $routeName, array $params = []): Action
    {
        $this->handlerType = HandlerType::URL;

        $this->handler = function ($record) use ($routeName, $params) {
            if(empty($params)){
                $params = ['record' => $record->id];
            }
            return route($routeName, $params);
        };

        return $this->toAction(); // automatically convert to Action
    }
}
