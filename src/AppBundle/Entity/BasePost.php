<?php
/**
 * Author: tom
 * Date: 06.10.17
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="typeDiscr", type="string")
 * @ORM\DiscriminatorMap({ "meme" = "Meme", "comment" = "Comment" })
 */
abstract class BasePost
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }


    /**
     * @param User $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }


    /**
     * @return string
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }


    /**
     * @param string $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }
}