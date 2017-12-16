<?php
namespace App\Subscriber;
use App\AppEvent;
use App\Entity\UserCard;
use App\Event\UserCardEvent;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Validator\Tests\Fixtures\Entity;
use Symfony\Component\Validator\Constraints\DateTime;
class UserCardSubscriber implements EventSubscriberInterface
{

    protected $em;
    protected $nbMaxUserCard;
    /**
     * PlayerSubscriber constructor.
     * @param $em
     */
    public function __construct(EntityManager $em,$nbMaxUserCard)
    {
        $this->em = $em;
        $this->nbMaxUserCard = $nbMaxUserCard;
    }
    public static function getSubscribedEvents()
    {
        return array(AppEvent::USER_CARD_ADD => 'usercardAdd',
                     AppEvent::USER_CARD_EDIT => 'usercardEdit',
                     AppEvent::USER_CARD_REMOVE => 'usercardRemove'
            );
    }
    public function usercardAdd(UserCardEvent $userCardEvent){

        $nbCarteJoueur = $this->em->getRepository(UserCard::class)->findBy(['user' => $userCardEvent->getUsercard()->getUser()]);

        if(count($nbCarteJoueur) < $this->nbMaxUserCard)
        {
            $usercard = $userCardEvent->getUsercard();
            $this->em->persist($usercard);
            $this->em->flush();
        }

    }

    public function usercardEdit(UserCardEvent $userCardEvent)
    {
        $usercard = $userCardEvent->getUsercard();
        $this->em->persist($usercard);
        $this->em->flush();
    }

    public function usercardRemove(UserCardEvent $userCardEvent)
    {
        $usercard = $userCardEvent->getUsercard();
        $this->em->remove($usercard);
        $this->em->flush();
    }

}