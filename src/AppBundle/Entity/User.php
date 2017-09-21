<?php
/**
 * Author: tom
 * Date: 10.09.17
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 */
class User
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
    private $name;

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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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


}