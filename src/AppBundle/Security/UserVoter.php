<?php

namespace AppBundle\Security;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use AppBundle\Entity\User;

/**
 * UserVoter.
 */
class UserVoter extends Voter
{
    const VIEW = 'view';

    public function supports($attribute, $subject)
    {
        return $subject instanceof User && in_array($attribute, array(
              self::VIEW
        ));
    }

    protected function voteOnAttribute($attribute, $currentUser, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        $roles = $user->getRoles();

        if (
            in_array('ROLE_USER', $roles) &&
            $attribute == self::VIEW &&
            $user->getEmail() === $currentUser->getEmail()
        ) {
            return true;
        }

        return false;
    }
}
