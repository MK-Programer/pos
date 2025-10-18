<?php

namespace App\Livewire\Widgets\Buttons;

use App\Enums\HandlerType;
use App\Enums\NotificationType;
use App\Livewire\Widgets\Notifications\Notify;
use Closure;
use Filament\Actions\Action;

use Throwable;
use Illuminate\Support\Facades\Log;

abstract class BaseButton
{
    protected string $name = 'action';
    protected ?string $label = null;
    protected ?string $icon = null;
    protected ?string $color = null;
    protected ?Closure $handler = null;
    protected ?string $successTitleMessage = null;
    protected ?string $successBodyMessage = null;
    protected ?string $errorTitleMessage = null;
    protected ?string $errorBodyMessage = null;
    protected bool $requiresConfirmation = false;
    protected ?HandlerType $handlerType = null;
    protected bool $openUrlInNewTab = false;

    // ===== Static factory method =====
    public static function make(): Action
    {
        $instance = new static();
        return $instance->toAction();
    }

    // === Fluent configuration ===
    public function name(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function label(string $label): static
    {
        $this->label = $label;
        return $this;
    }

    public function icon(string $icon): static
    {
        $this->icon = $icon;
        return $this;
    }

    public function color(string $color): static
    {
        $this->color = $color;
        return $this;
    }

    public function onAction(Closure $handler): static
    {
        $this->handlerType = HandlerType::ACTION;
        $this->handler = $handler;
        return $this;
    }

    public function toRoute(Closure $handler): static
    {
        $this->handlerType = HandlerType::URL;
        $this->handler = $handler;
        return $this;
    }

    public function successTitleMessage(string $message): static
    {
        $this->successTitleMessage = $message;
        return $this;
    }

    public function successBodyMessage(string $message): static
    {
        $this->successBodyMessage = $message;
        return $this;
    }

    public function errorTitleMessage(string $message): static
    {
        $this->errorTitleMessage = $message;
        return $this;
    }

    public function errorBodyMessage(string $message): static
    {
        $this->errorBodyMessage = $message;
        return $this;
    }

    public function requiresConfirmation(bool $state = true): static
    {
        $this->requiresConfirmation = $state;
        return $this;
    }

    public function openUrlInNewTab(bool $condition = true): static
    {
        $this->openUrlInNewTab = $condition;
        return $this;
    }

    // === Build the Filament Action ===
    public function toAction(): Action
    {
        $action = Action::make($this->name)
            ->label($this->label)
            ->icon($this->icon)
            ->color($this->color);

        if ($this->handlerType == HandlerType::ACTION) {
            $action->action(function ($record, Action $action) {
                try {
                    if ($this->handler instanceof Closure) {
                        ($this->handler)($record, $action);
                    }

                    if ($this->successTitleMessage || $this->successBodyMessage) {
                        Notify::send($this->successTitleMessage, $this->successBodyMessage);
                    }
                } catch (Throwable $e) {
                    Log::error("Action {$this->name} failed: {$e->getMessage()}");
                    if(!$this->errorBodyMessage){
                        $this->errorBodyMessage = $e->getMessage();
                    }
                    Notify::send($this->errorTitleMessage, $this->errorBodyMessage, NotificationType::DANGER);
                    
                }
            });
        } else if ($this->handlerType == HandlerType::URL) {
            $action->url(function ($record) {
                try {

                    return $this->handler instanceof Closure ? ($this->handler)($record) : '#';
                } catch (Throwable $e) {
                    Log::error("Action {$this->name} failed: {$e->getMessage()}");

                    if(!$this->errorBodyMessage){
                        $this->errorBodyMessage = $e->getMessage();
                    }
                    Notify::send($this->errorTitleMessage, $this->errorBodyMessage, NotificationType::DANGER);
                }
            })->openUrlInNewTab($this->openUrlInNewTab);
        }

        if ($this->requiresConfirmation) {
            $action->requiresConfirmation();
        }

        return $action;
    }
}
