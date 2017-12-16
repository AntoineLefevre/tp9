<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 16/12/2017
 * Time: 21:11
 */
namespace App\Event;

use Symfony\Component\EventDispatcher\Event;

class UserCardEvent extends Event
{
    private $usercard;
    /**
     * @return mixed
     */
    public function getUsercard()
    {
        return $this->usercard;
    }
    /**
     * @param mixed $usercard
     */
    public function setUsercard($usercard)
    {
        $this->usercard = $usercard;
    }
}