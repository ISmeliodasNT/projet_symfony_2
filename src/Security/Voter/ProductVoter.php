<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProductVoter extends Voter
{
    // On définit le nom de notre permission
    public const LIST = 'PRODUCT_LIST';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // On vérifie seulement si l'attribut demandé est bien PRODUCT_LIST
        // On ne vérifie pas le $subject car pour une liste, il n'y a pas d'objet spécifique
        return in_array($attribute, [self::LIST]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // Si l'utilisateur n'est pas connecté, on refuse l'accès
        if (!$user instanceof UserInterface) {
            return false;
        }

        // Si on arrive ici, c'est que l'utilisateur est connecté.
        // Puisque tu veux donner l'accès à "tous les utilisateurs connectés", on retourne true.
        
        switch ($attribute) {
            case self::LIST:
                return true; // Accès autorisé pour tout utilisateur connecté
        }

        return false;
    }
}