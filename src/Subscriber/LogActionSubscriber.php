<?php

namespace App\Subscriber;

use App\AppEvent;
use App\Entity\LogAction;
use App\Entity\UserCard;
use App\Event\UserCardEvent;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Validator\Tests\Fixtures\Entity;
use Symfony\Component\Validator\Constraints\DateTime;

class LogActionSubscriber implements EventSubscriberInterface
{

    protected $em;
    protected $logAction;
    protected $token;

    public function __construct(EntityManager $em,LogAction $logAction, TokenStorage $token)
    {
        $this->em = $em;
        $this->logAction = $logAction;
        $this->token = $token;
    }
    public static function getSubscribedEvents()
    {
        return array(AppEvent::USER_CARD_ADD => 'usercardAdd',
            AppEvent::USER_CARD_EDIT => 'usercardEdit',
            AppEvent::USER_CARD_REMOVE => 'usercardRemove'
        );
    }
    public function usercardAdd(UserCardEvent $userCardEvent){
        $this->persist($userCardEvent,'ADD');
    }

    public function usercardEdit(UserCardEvent $userCardEvent)
    {
        $this->persist($userCardEvent,'EDIT');
    }

    public function usercardRemove(UserCardEvent $userCardEvent)
    {
        $this->persist($userCardEvent,'REMOVE');
    }

    public function persist(UserCardEvent $userCardEvent, $type)
    {
        $date = new \DateTime('now');
        $this->logAction->setLog($date->format('d/m/Y h:i:s') .' '. $type . 'card'.' '.
            $userCardEvent->getUsercard()->getCard()->getName().' '.
            $this->token->getToken()->getUser()->getEmail()
        );
        $this->em->persist($this->logAction);
        $this->em->flush();
    }

}