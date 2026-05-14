<?php

namespace App\Enums;

enum ApprovalStatus: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case UnderReview = 'under_review';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Submitted => 'Submitted',
            self::UnderReview => 'Under review',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Draft => 'bg-zinc-200 text-zinc-700 dark:bg-zinc-700/40 dark:text-zinc-200',
            self::Submitted => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300',
            self::UnderReview => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300',
            self::Approved => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300',
            self::Rejected => 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300',
        };
    }

    public function isVisibleOnSite(): bool
    {
        return $this === self::Approved;
    }
}
