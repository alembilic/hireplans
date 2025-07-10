<?php

namespace App\Enums;

enum EmployerStatus: int
{
    case IN_PROGRESS = 1;
    case ACTIVE_OPPORTUNITY = 2;
    case CURRENT_CLIENT = 3;
    case DEAD_OPPORTUNITY = 4;
    case DO_NOT_PROSPECT = 5;
    case UNCONTACTED = 6;

    public function label(): string
    {
        return match($this) {
            self::IN_PROGRESS => 'In Progress',
            self::ACTIVE_OPPORTUNITY => 'Active Opportunity',
            self::CURRENT_CLIENT => 'Current Client',
            self::DEAD_OPPORTUNITY => 'Dead Opportunity',
            self::DO_NOT_PROSPECT => 'Do Not Prospect',
            self::UNCONTACTED => 'Uncontacted',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::IN_PROGRESS => 'blue',
            self::ACTIVE_OPPORTUNITY => 'green',
            self::CURRENT_CLIENT => 'purple',
            self::DEAD_OPPORTUNITY => 'red',
            self::DO_NOT_PROSPECT => 'gray',
            self::UNCONTACTED => 'yellow',
        };
    }

    public static function fromValue(int $value): ?self
    {
        return match($value) {
            1 => self::IN_PROGRESS,
            2 => self::ACTIVE_OPPORTUNITY,
            3 => self::CURRENT_CLIENT,
            4 => self::DEAD_OPPORTUNITY,
            5 => self::DO_NOT_PROSPECT,
            6 => self::UNCONTACTED,
            default => null,
        };
    }
}
