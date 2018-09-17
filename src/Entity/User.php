<?php
/**
 * Created by PhpStorm.
 * User: UTILISATEUR
 * Date: 11/01/2018
 * Time: 09:34
 */

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Cette email est déjà enregistrer")
 * @UniqueEntity(fields="username", message="Ce nom d'utilisateur est enregistrer")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     *
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var array
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $roles;

    /**
     * @ORM\Column(name="matricule_conducteur", nullable=true, type="string")
     */
    private $matricule_conducteur;


    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Conducteur", mappedBy="user", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $conducteur;


    public function __construct()
    {
        $this->isActive = true;
        $this->roles = ['ROLE_USER'];
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid('', true));
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        $tmpRoles = $this->roles;

        if($tmpRoles !== ['ROLE_USER'] && $tmpRoles !== ['ROLE_ADMIN'] && $tmpRoles !== ['ROLE_MASTER'] && $tmpRoles !== ['ROLE_SUPER_MASTER'] && $tmpRoles !== ['ROLE_RH'] && $tmpRoles !== ['ROLE_EXPLOIT'])
        {
            $tmpRoles = ['ROLE_USER'];
        }

        return $tmpRoles;
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }





    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getisActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @param array $roles
     */
    public function setRoles( array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getMatriculeConducteur()
    {
        return $this->matricule_conducteur;
    }

    /**
     * @param mixed $matricule_conducteur
     */
    public function setMatriculeConducteur($matricule_conducteur): void
    {
        $this->matricule_conducteur = $matricule_conducteur;
    }

    /**
     * @return mixed
     */
    public function getConducteur()
    {
        return $this->conducteur;
    }

    /**
     * @param mixed $conducteur
     */
    public function setConducteur(Conducteur $conducteur): void
    {
        $this->conducteur = $conducteur;
    }




}