<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="tp_usercard")
 */
class UserCard
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="integer")
     */
    private $attack = 2;
    /**
     * @ORM\Column(type="integer")
     */
    private $defense = 2;
    /**
     * @ORM\Column(type="integer", name="action_point")
     */
    private $actionPoint = 2;
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity="Card")
     * @ORM\JoinColumn(name="card_id", referencedColumnName="id")
     */
    private $card;
    /**
     * @return mixed
     */
    public function getAttack()
    {
        return $this->attack;
    }
    /**
     * @param mixed $attack
     */
    public function setAttack($attack)
    {
        $this->attack = $attack;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getDefense()
    {
        return $this->defense;
    }
    /**
     * @param mixed $defense
     */
    public function setDefense($defense)
    {
        $this->defense = $defense;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getActionPoint()
    {
        return $this->actionPoint;
    }
    /**
     * @param mixed $actionPoint
     */
    public function setActionPoint($actionPoint)
    {
        $this->actionPoint = $actionPoint;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getCard()
    {
        return $this->card;
    }
    /**
     * @param mixed $card
     */
    public function setCard($card)
    {
        $this->card = $card;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}