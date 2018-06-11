<?php
/**
 * Author: tom
 * Date: 11.06.18
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */
class Tag {


	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=128)
	 */
	private $name;

	/**
	 * @var bool
	 * @ORM\Column(type="boolean")
	 */
	private $autocomplete;

	/**
	 * @var Meme[]
	 */
	private $memes;


	public function __construct()
	{
		$this->memes = new ArrayCollection();
	}


	/**
	 * @param string $name
	 */
	public function setName(string $name)
	{
		$this->name = $name;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @param bool $autocomplete
	 */
	public function setAutocomplete($autocomplete)
	{
		$this->autocomplete = $autocomplete;
	}


	/**
	 * @return bool
	 */
	public function getAutocomplete()
	{
		return $this->autocomplete;
	}


	/**
	 * @param Meme $meme
	 */
	public function addMeme(Meme $meme)
	{
		$this->memes[] = $meme;
	}
}