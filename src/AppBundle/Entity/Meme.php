<?php
/**
 * Author: tom
 * Date: 08.10.17
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Meme extends BasePost
{
    /**
     * @ORM\Column(type="string", length=128)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $file;

    /**
     * @ORM\Column(type="text")
     */
    private $description;
}