<?php
namespace App\Event;
use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;
class UserEvent extends Event
{
    protected $user;
    public function getUser()
    {
        return $this->user;
    }
    public function setUser(User $user)
    {
        $this->user = $user;
    }
}