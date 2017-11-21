<?php
/**
 * Author: tom
 * Date: 19.11.17
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Comment extends BasePost
{

    /**
     * @var BasePost
     * @ORM\ManyToOne(targetEntity="BasePost")
     */
    private $subject;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $text;


    /**
     * @return BasePost
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }


    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }


    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }
}