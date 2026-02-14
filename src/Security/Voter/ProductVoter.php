<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProductVoter extends Voter
{
    public const LIST = 'PRODUCT_LIST';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::LIST]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }
        
        switch ($attribute) {
            case self::LIST:
                return true;
        }

        return false;
    }
}