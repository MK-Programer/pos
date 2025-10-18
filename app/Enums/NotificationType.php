<?php

namespace App\Enums;

enum NotificationType: string
{
    case SUCCESS = 'success';
    case DANGER = 'danger';
    case WARNING = 'warning';
    case INFO = 'info';
}
