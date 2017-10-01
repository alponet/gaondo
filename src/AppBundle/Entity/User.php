<?php
/**
 * Author: tom
 * Date: 10.09.17
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
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
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $regDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $activeDate;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $avatarUrl;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param string $name
     */
    public function setUsername($name)
    {
        $this->username = $name;
    }


    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }


    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }


    /**
     * @return string
     */
    public function getRegDate()
    {
        return $this->regDate;
    }


    /**
     * @param string $regDate
     */
    public function setRegDate($regDate)
    {
        $this->regDate = $regDate;
    }


    /**
     * @return string
     */
    public function getActiveDate()
    {
        return $this->activeDate;
    }


    /**
     * update activity date
     */
    public function updateActiveDate()
    {
        // ToDo
    }


    /**
     * @return string
     */
    public function getAvatarUrl()
    {
        return $this->avatarUrl;
    }


    /**
     * @param string $avatarUrl
     */
    public function setAvatarUrl($avatarUrl)
    {
        $this->avatarUrl = $avatarUrl;
    }


    /**
     * Returns the roles granted to the user.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return [ 'ROLE_USER' ];
    }


    /**
     * Returns the salt that was originally used to encode the password.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }


    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->username;
    }


    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        return null;
    }


    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password
        ]);
    }


    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password
        ) = unserialize($serialized);
    }
}