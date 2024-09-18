<?php

namespace App\Security\Voter;

use App\Entity\GrumpyPizza;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class PizzaVoter extends Voter
{
    public const SHOW = 'PIZZA_SHOW';
    public const CREATE = 'PIZZA_CREATE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::SHOW, self::CREATE])
            && $subject instanceof GrumpyPizza;
    }

    /**
     * @param GrumpyPizza $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::SHOW:
                // logic to determine if the user can EDIT
                // return true or false
                return true;

            case self::CREATE:
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}
