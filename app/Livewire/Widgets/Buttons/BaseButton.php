<?php

namespace App\Livewire\Widgets\Buttons;

use Closure;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

use Throwable;
use Illuminate\Support\Facades\Log;

abstract class BaseButton
{
    protected string $name = 'action';
    protected ?string $label = null;
    protected ?string $icon = null;
    protected ?string $color = null;
    protected ?Closure $handler = null;
    protected ?string $successMessage = null;
    protected ?string $errorMessage = null;
    protected bool $requiresConfirmation = false;

    // ===== Static factory method =====
    public static function make(): Action
    {
        $instance = new static();
        return $instance->toAction();
    }

    // === Fluent configuration ===
    public function name(string $name): static { $this->name = $name; return $this; }
    public function label(string $label): static { $this->label = $label; return $this; }
    public function icon(string $icon): static { $this->icon = $icon; return $this; }
    public function color(string $color): static { $this->color = $color; return $this; }
    public function onAction(Closure $handler): static { $this->handler = $handler; return $this; }
    public function successMessage(string $message): static { $this->successMessage = $message; return $this; }
    public function errorMessage(string $message): static { $this->errorMessage = $message; return $this; }
    public function requiresConfirmation(bool $state = true): static { $this->requiresConfirmation = $state; return $this; }

    // === Build the Filament Action ===
    public function toAction(): Action
    {
        $action = Action::make($this->name)
            ->label($this->label)
            ->icon($this->icon)
            ->color($this->color)
            ->action(function ($record, Action $action) {
                try {
                    if ($this->handler instanceof Closure) {
                        ($this->handler)($record, $action);
                    }

                    if ($this->successMessage) {
                        Notification::make()
                            ->title($this->successMessage)
                            ->success()
                            ->send();
                    }
                } catch (Throwable $e) {
                    Log::error("Action {$this->name} failed: {$e->getMessage()}");

                    if ($this->errorMessage) {
                        Notification::make()
                            ->title($this->errorMessage)
                            ->danger()
                            ->send();
                    }
                }
            });

        if ($this->requiresConfirmation) {
            $action->requiresConfirmation();
        }

        return $action;
    }
}
