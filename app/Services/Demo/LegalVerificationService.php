<?php

namespace App\Services\Demo;

use App\Models\Property;

/**
 * Simulated legal-verification workflow. Real-world title verification
 * involves manual lawyer review, document checks, encumbrance certificates
 * and so on. This service produces a *demonstration* status badge based on
 * how complete the listing's documents and metadata are.
 */
class LegalVerificationService
{
    public const STATUS_NOT_VERIFIED = 'not_verified';
    public const STATUS_IN_PROGRESS  = 'in_progress';
    public const STATUS_VERIFIED     = 'verified';
    public const STATUS_ATTENTION    = 'needs_attention';

    public function evaluate(Property $property): array
    {
        if (! config('estatify.demo.legal_verification', true)) {
            return $this->payload(self::STATUS_NOT_VERIFIED, 'Demo legal verification is disabled.');
        }

        $property->loadMissing('documents');
        $documents = $property->documents;
        $score = 0;
        $reasons = [];

        if ($documents->where('type', 'deed')->isNotEmpty())  { $score += 35; $reasons[] = 'Title deed on file'; }
        if ($documents->where('type', 'tax')->isNotEmpty())   { $score += 20; $reasons[] = 'Latest tax receipt available'; }
        if ($documents->where('type', 'noc')->isNotEmpty())   { $score += 15; $reasons[] = 'NOC supplied'; }
        if ($documents->where('type', 'plan')->isNotEmpty())  { $score += 10; $reasons[] = 'Approved plan attached'; }

        if (! empty($property->survey_number))                { $score += 10; $reasons[] = 'Survey number on record'; }
        if ($property->approved_at)                           { $score += 10; $reasons[] = 'Cleared admin moderation'; }

        $status = match (true) {
            $score >= 70 => self::STATUS_VERIFIED,
            $score >= 40 => self::STATUS_IN_PROGRESS,
            $score >  0  => self::STATUS_ATTENTION,
            default      => self::STATUS_NOT_VERIFIED,
        };

        return $this->payload($status, 'Simulated demo verification', $reasons, $score);
    }

    public function statusLabel(string $status): string
    {
        return match ($status) {
            self::STATUS_VERIFIED    => 'Verified (demo)',
            self::STATUS_IN_PROGRESS => 'In progress (demo)',
            self::STATUS_ATTENTION   => 'Needs attention (demo)',
            default                  => 'Not verified',
        };
    }

    public function badgeTone(string $status): string
    {
        return match ($status) {
            self::STATUS_VERIFIED    => 'green',
            self::STATUS_IN_PROGRESS => 'blue',
            self::STATUS_ATTENTION   => 'amber',
            default                  => 'zinc',
        };
    }

    /**
     * @return array{status: string, message: string, reasons: array<int, string>, score: int}
     */
    private function payload(string $status, string $message, array $reasons = [], int $score = 0): array
    {
        return compact('status', 'message', 'reasons', 'score');
    }
}
