<?php

namespace App\Livewire\Widgets\Actions;

use App\Enums\HandlerType;
use App\Enums\NotificationType;
use App\Livewire\Widgets\Notifications\Notify;
use Closure;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Throwable;

abstract class BaseAction
{
    protected string $name = 'action';
    protected ?string $label = null;
    protected ?string $icon = null;
    protected ?string $color = null;
    protected ?Closure $handler = null;
    protected ?HandlerType $handlerType = null;
    protected bool $requiresConfirmation = false;
    protected bool $openUrlInNewTab = false;
    protected ?string $successTitle = null;
    protected ?string $successMessage = null;
    protected ?string $errorTitle = null;
    protected ?string $errorMessage = null;

    // ===== Factory =====
    public static function make()
    {
        return new static();
    }

    // ===== Fluent setters =====
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;
        return $this;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;
        return $this;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;
        return $this;
    }

    public function confirmBeforeAction(bool $state = true): static
    {
        $this->requiresConfirmation = $state;
        return $this;
    }

    public function openInNewTab(bool $state = true): static
    {
        $this->openUrlInNewTab = $state;
        return $this;
    }

    public function success(?string $title = null, ?string $message = null): static
    {
        $this->successTitle = $title;
        $this->successMessage = $message;
        return $this;
    }

    public function error(?string $title = null, ?string $message = null): static
    {
        $this->errorTitle = $title;
        $this->errorMessage = $message;
        return $this;
    }

    // ===== Action handlers =====
    public function handleAction(Closure $handler): Action
    {
        $this->handlerType = HandlerType::ACTION;
        $this->handler = $handler;
        return $this->toAction(); // automatically convert to Action
    }

    // ===== Convert to Filament Action =====
    public function toAction(): Action
    {
        $action = Action::make($this->name)
            ->label($this->label)
            ->icon($this->icon)
            ->color($this->color);

        if ($this->handlerType === HandlerType::ACTION) {
            $action->action(function ($record, Action $action) {
                try {
                    ($this->handler)($record, $action);
                    if ($this->successTitle || $this->successMessage) {
                        Notify::send($this->successTitle, $this->successMessage);
                    }
                } catch (Throwable $e) {
                    Log::error("Action {$this->name} failed: {$e->getMessage()}");
                    if (!$this->errorMessage) $this->errorMessage = $e->getMessage();
                    Notify::send($this->errorTitle, $this->errorMessage, NotificationType::DANGER);
                }
            });
        }

        if ($this->handlerType === HandlerType::URL) {
            $action->url(fn($record) => $this->handler instanceof Closure ? ($this->handler)($record) : '#');
            if ($this->openUrlInNewTab) $action->openUrlInNewTab();
        }

        if ($this->requiresConfirmation) $action->requiresConfirmation();

        return $action;
    }
}
