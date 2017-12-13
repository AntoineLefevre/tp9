<?php
namespace App\Event;
use App\Entity\UserCard;
use Symfony\Component\EventDispatcher\Event;
class UserCardEvent extends Event
{
    protected $userCard;
    public function getUserCard()
    {
        return $this->userCard;
    }
    public function setUserCard(UserCard $userCard)
    {
        $this->userCard = $userCard;
    }
}