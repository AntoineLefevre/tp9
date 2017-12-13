<?php

namespace App\EventListener;

use App\AppEvent;
use App\Entity\UserCard;
use App\Event\UserCardEvent;
use App\Event\UserEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserSubscriber implements EventSubscriberInterface
{

    private $em;
    private $encoder;

    public function __construct(EntityManagerInterface $entityManager,UserPasswordEncoderInterface $encoder)
    {
        $this->em = $entityManager;
        $this->encoder = $encoder;
    }

    public static function getSubscribedEvents()
    {
        return [
            AppEvent::USER_ADD => array('add', 0),
            AppEvent::USER_EDIT => array('edit', 0),
        ];
    }


    public function add(UserEvent $userEvent)
    {
        $user = $userEvent->getUser();
        $user->setPassword($this->encoder->encodePassword($user,$user->getPassword()));
        $this->em->persist($user);
        $this->em->flush();

    }

    public function edit(UserEvent $userEvent)
    {
        $this->add($userEvent);
    }
}