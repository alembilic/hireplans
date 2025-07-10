<?php

namespace App\Enums;

enum JobApplicationStatus: int
{
    case APPLIED = 0;
    case LONGLIST = 1;
    case SHORTLIST = 2;
    case OUTBOUND = 3;
    case SCREENING = 4;
    case SUBMITTED = 5;
    case INTERVIEWING = 6;
    case REJECTED = 7;
    case HIRED = 8;

    public function label(): string
    {
        return match($this) {
            self::APPLIED => 'Applied',
            self::LONGLIST => 'Longlist',
            self::SHORTLIST => 'Shortlist',
            self::OUTBOUND => 'Outbound',
            self::SCREENING => 'Screening',
            self::SUBMITTED => 'Submitted',
            self::INTERVIEWING => 'Interviewing',
            self::REJECTED => 'Rejected',
            self::HIRED => 'Hired',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::APPLIED => 'bg-blue-100 text-blue-700',
            self::LONGLIST => 'bg-yellow-100 text-yellow-700',
            self::SHORTLIST => 'bg-orange-100 text-orange-700',
            self::OUTBOUND => 'bg-purple-100 text-purple-700',
            self::SCREENING => 'bg-indigo-100 text-indigo-700',
            self::SUBMITTED => 'bg-cyan-100 text-cyan-700',
            self::INTERVIEWING => 'bg-pink-100 text-pink-700',
            self::REJECTED => 'bg-red-100 text-red-700',
            self::HIRED => 'bg-green-100 text-green-700',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($status) => [
            $status->value => $status->label()
        ])->toArray();
    }

    public static function fromValue(int $value): ?self
    {
        return match($value) {
            0 => self::APPLIED,
            1 => self::LONGLIST,
            2 => self::SHORTLIST,
            3 => self::OUTBOUND,
            4 => self::SCREENING,
            5 => self::SUBMITTED,
            6 => self::INTERVIEWING,
            7 => self::REJECTED,
            8 => self::HIRED,
            default => null,
        };
    }
} 