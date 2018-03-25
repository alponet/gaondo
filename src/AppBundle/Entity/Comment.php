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
     * @var Meme
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Meme", inversedBy="comments")
     */
    private $subject;

	/**
	 * @var Comment
	 * @ORM\ManyToOne(targetEntity="Comment")
	 */
    private $replyTo;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string
     */
    private $text;


    /**
     * @return Meme
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
	 * @return Comment
	 */
    public function getReplyTo()
    {
    	return $this->replyTo;
    }


	/**
	 * @param $comment
	 */
    public function setReplyTo($comment)
    {
    	$this->replyTo = $comment;
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