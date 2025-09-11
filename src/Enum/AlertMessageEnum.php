<?php

namespace App\Enum;

enum AlertMessageEnum: string
{

    case PRIMARY = 'primary';
    case SECONDARY = 'secondary';
    case SUCCESS = 'success';
    case DANGER = 'danger';
    case WARNING = 'warning';
    case INFO = 'info';
    case LIGHT = 'light';
    case DARK = 'dark';

    public function label(): string {
        return match ($this) {
            self::PRIMARY => 'exclamation-circle',
            self::SECONDARY => 'exclamation-circle',
            self::SUCCESS => 'check-circle',
            self::DANGER => 'exclamation-diamond',
            self::WARNING => 'exclamation-diamond',
            self::LIGHT => 'exclamation-circle',
            self::DARK => 'exclamation-circle-fill'
        };
    }
}
