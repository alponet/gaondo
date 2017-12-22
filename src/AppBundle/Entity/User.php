<?php
/**
 * Author: tom
 * Date: 10.09.17
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 * @Vich\Uploadable
 */
class User extends BaseUser
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $avatarUrl;

	/**
	 * @Vich\UploadableField(mapping="avatar_image", fileNameProperty="avatarUrl")
	 *
	 * @var File
	 */
    protected $avatarFile;

    /**
     * @ORM\OneToMany(targetEntity="BasePost", mappedBy="author")
     */
    protected $posts;


    /**
     * User constructor.
     */
    public function __construct()
    {
    	parent::__construct();
    	$this->avatarUrl = 'default.png';
        $this->posts = new ArrayCollection();
    }


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
    public function getAvatarUrl()
    {
	    if (!is_null($this->avatarUrl)) {
		    return "/images/avatars/" . $this->avatarUrl;
	    }

	    return '/images/avatars/default.png';
    }


    /**
     * @param string $avatarUrl
     */
    public function setAvatarUrl($avatarUrl)
    {
        $this->avatarUrl = $avatarUrl;
    }


	/**
	 * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
	 */
    public function setAvatarFile(File $file = null)
    {
    	$this->avatarFile = $file;

	    if ($file) {
		    $this->setLastLogin(new \DateTime('now'));
	    }
    }


	/**
	 * @return File|null
	 */
    public function getAvatarFile()
    {
    	return $this->avatarFile;
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