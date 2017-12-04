<?php
/**
 * Author: tom
 * Date: 04.12.17
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Vote extends BasePost {

	/**
	 * @var BasePost
	 * @ORM\ManyToOne(targetEntity="BasePost")
	 */
	private $subject;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $count;


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
	 * @return integer
	 */
	public function getCount()
	{
		return $this->count;
	}


	/**
	 * @param $number integer
	 */
	public function setCount($number)
	{
		$this->count = $number;
	}
}