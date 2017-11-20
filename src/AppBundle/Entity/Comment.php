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
     * @ORM\Column(type="string", length=128)
     *
     * @var string
     */
    private $text;


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
    public function setText(string $text)
    {
        $this->text = $text;
    }
}