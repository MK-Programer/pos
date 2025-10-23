<?php

namespace App\Livewire\Widgets\Notifications;

use Filament\Notifications\Notification;
use App\Enums\NotificationType;

class Notify
{
    public static function send(
        string $title,
        ?string $body = null,
        NotificationType $type = NotificationType::SUCCESS
    ): void {
        $notification = Notification::make()
            ->title($title)
            ->body($body.'.');

        match ($type) {
            NotificationType::SUCCESS => $notification->success(),
            NotificationType::DANGER => $notification->danger(),
            NotificationType::WARNING => $notification->warning(),
            NotificationType::INFO => $notification->info(),
        };

        $notification->send();
    }
}
