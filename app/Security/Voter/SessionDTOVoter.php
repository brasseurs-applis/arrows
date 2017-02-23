<?php

namespace BrasseursApplis\Arrows\App\Security\Voter;

use BrasseursApplis\Arrows\App\DTO\SessionDTO;
use BrasseursApplis\Arrows\App\Security\AuthorizationUser;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SessionDTOVoter extends Voter
{
    const ACCESS = 'access';
    const OBSERVE = 'observe';
    const RESPOND = 'respond';
    const PREVIEW = 'preview';

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed  $subject   The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [ self::ACCESS, self::OBSERVE, self::RESPOND, self::PREVIEW ], true)) {
            return false;
        }

        // only vote on Session objects inside this voter
        if (!$subject instanceof SessionDTO) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string         $attribute
     * @param SessionDTO     $subject
     * @param TokenInterface $token
     *
     * @return bool
     *
     * @throws \LogicException
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof AuthorizationUser) {
            // the user must be logged in; if not, deny access
            return false;
        }

        switch ($attribute) {
            case self::ACCESS:
                return $this->canAccess($subject, $user);
            case self::OBSERVE:
                return $this->canObserve($subject, $user);
            case self::RESPOND:
                return $this->canRespond($subject, $user);
            case self::PREVIEW:
                return $this->canPreview($subject, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @param SessionDTO        $subject
     * @param AuthorizationUser $user
     *
     * @return bool
     */
    private function canAccess(SessionDTO $subject, AuthorizationUser $user)
    {
        return $this->canObserve($subject, $user)
            || $this->canRespond($subject, $user)
            || $this->canPreview($subject, $user);
    }

    /**
     * @param SessionDTO        $subject
     * @param AuthorizationUser $user
     *
     * @return bool
     */
    private function canObserve(SessionDTO $subject, AuthorizationUser $user)
    {
        return $subject->getResearcher()->getId() === $user->getId();
    }

    /**
     * @param SessionDTO        $subject
     * @param AuthorizationUser $user
     *
     * @return bool
     */
    private function canRespond(SessionDTO $subject, AuthorizationUser $user)
    {
        return $subject->getSubjectOne()->getId() === $user->getId();
    }

    /**
     * @param SessionDTO        $subject
     * @param AuthorizationUser $user
     *
     * @return bool
     */
    private function canPreview(SessionDTO $subject, AuthorizationUser $user)
    {
        return $subject->getSubjectTwo()->getId() === $user->getId();
    }
}
