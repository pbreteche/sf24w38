<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class WorkingDayVoter extends Voter
{
    public const WORKED = 'IS_WORKED';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::WORKED === $attribute;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $today = new \DateTimeImmutable('today');

        return !in_array($today->format('N'), ['6', '7']);
    }
}
