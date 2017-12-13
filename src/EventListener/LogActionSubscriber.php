<?php

namespace App\EventListener;

use App\AppEvent;
use App\Entity\LogAction;
use App\Event\UserCardEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LogActionSubscriber implements EventSubscriberInterface
{

    private $em;
    private $logAction;
    private $token;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $token, LogAction $logAction)
    {
        $this->em = $entityManager;
        $this->logAction = $logAction;
        $this->token = $token;
    }

    public static function getSubscribedEvents()
    {
        return [
            AppEvent::USER_CARD_ADD => array('add', -255),
            AppEvent::USER_CARD_EDIT => array('update', -255),
            AppEvent::USER_CARD_DELETE => array('remove', -255),
        ];
    }


    public function add(UserCardEvent $userCardEvent)
    {
        $this->persist($userCardEvent, 'ADD');
    }

    public function update(UserCardEvent $userCardEvent)
    {
        $this->persist($userCardEvent, 'UPDATE');
    }

    public function remove(UserCardEvent $userCardEvent)
    {
        $this->persist($userCardEvent, 'DELETE');
    }

    public function persist(UserCardEvent $userCardEvent, $type)
    {
        $date = new \DateTime('now');
        $this->logAction->setLog($date->format('d/m/Y h:i:s') . ' : ' . $type . ' card ' .
            $userCardEvent->getUserCard()->getCard()->getName() . ' by '
            . $this->token->getToken()->getUser()->getEmail());
        $this->em->persist($this->logAction);
        $this->em->flush();
    }
}