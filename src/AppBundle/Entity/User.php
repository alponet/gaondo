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
     * @ORM\Column(type="string", length=64)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=128)
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

}