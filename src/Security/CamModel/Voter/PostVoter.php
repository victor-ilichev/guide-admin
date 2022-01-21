<?php

declare(strict_types=1);

namespace App\Security\CamModel\Voter;

use App\Entity\CamModel\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PostVoter extends Voter
{
    const CREATE = 'create';
    const EDIT   = 'edit';
    const DELETE = 'delete';

    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::CREATE, self::EDIT, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Post) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        /** @var Post $subject */

        if (!$user instanceof User) {
            return false;
        }

        switch ($attribute) {
            // if the user is an admin, allow them to create new posts
            case self::CREATE:
                if ($this->decisionManager->decide($token, ['ROLE_STUDIO_MANAGER'])) {
                    return true;
                }

                break;

            // if the user is the author of the post, allow them to edit the posts
            case self::EDIT:
            case self::DELETE:
                if ($user->getId() === $subject->getUser()->getId()) {
                    return true;
                }

                break;
        }

        return false;
    }
}
