<?php

namespace App\Security;

use App\AppAccess;
use App\Entity\User;
use App\Entity\UserCard;

use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{

    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }



    const EDIT = AppAccess::UserEdit;


    protected function supports($attribute, $subject)
    {
        if(!in_array($attribute, array(self::EDIT))){
            return false;
        }

        if(!$subject instanceof User){
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if(!$user instanceof User){
            return false;
        }

        if ($this->decisionManager->decide($token, array('ROLE_ADMIN')) === true ) {
            return true;
        }

        switch($attribute){
            case self::EDIT:
                return $subject->getId() === $user->getId();
            default:
                throw new \LogicException('This code should not be reached!');
        }
    }
}