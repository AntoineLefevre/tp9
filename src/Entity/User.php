<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 * @ORM\Table(name="tp_user")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $email;
    /**
     * @ORM\Column(type="string")
     */
    private $firstname;
    /**
     * @ORM\Column(type="string")
     */
    private $lastname;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min = 5,
     *     max = 50,
     *     minMessage="Your password must be at least {{ limit }} characters long",
     *     maxMessage="YOur password cannot be longer than {{ limit }} characters long",
     *     groups = {"new"})
     *
     */
    private $password;
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isAdmin = false;
    public function __construct()
    {
        $this->createdAt = $this->updatedAt = new \DateTime();
    }
    public function getId() : ?int
    {
        return $this->id;
    }
    public function getEmail() : ?string
    {
        return $this->email;
    }
    public function setEmail(?string $email) : void
    {
        $this->email = $email;
    }
    public function getFirstname() : ?string
    {
        return $this->firstname;
    }
    public function setFirstname(?string $firstname) : void
    {
        $this->firstname = $firstname;
    }
    public function getLastname() : ?string
    {
        return $this->lastname;
    }
    public function setLastname(?string $lastname) : void
    {
        $this->lastname = $lastname;
    }

    public function getPassword() : ?string
    {
        return $this->password;
    }
    public function setPassword(?string $password) : void
    {
        $this->password = $password;
    }
    public function getCreatedAt() : \DateTime
    {
        return $this->createdAt;
    }
    public function getUpdatedAt() : \DateTime
    {
        return $this->updatedAt;
    }
    public function setUpdatedAt(\DateTime $updatedAt) : void
    {
        $this->updatedAt = $updatedAt;
    }
    public function isAdmin() : bool
    {
        return $this->isAdmin;
    }
    public function setIsAdmin(bool $isAdmin) : void
    {
        $this->isAdmin = $isAdmin;
    }
    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        $roles = ['ROLE_USER'];
        if ($this->isAdmin()) {
            $roles[] = 'ROLE_ADMIN';
        }
        return $roles;
    }
    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
    }
    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->email;
    }
    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }
    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
            $this->firstname,
            $this->lastname,
            $this->isAdmin,
            $this->password,
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->firstname,
            $this->lastname,
            $this->isAdmin,
            $this->password) = unserialize($serialized);
    }
}