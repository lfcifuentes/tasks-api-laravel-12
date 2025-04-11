<?php

namespace App\Enums;

enum TaskStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'gray',
            self::COMPLETED => 'green',
        };
    }

    public static function getValues(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
