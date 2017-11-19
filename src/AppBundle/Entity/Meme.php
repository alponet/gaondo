<?php
/**
 * Author: tom
 * Date: 08.10.17
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Meme extends BasePost
{
    /**
     * @ORM\Column(type="string", length=128)
     */
    private $title;

    /**
     * @Vich\UploadableField(mapping="meme_image", fileNameProperty="imageName", size="imageSize")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=128)
     *
     * @var string
     */
    private $imageName;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    private $imageSize;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;


    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }


    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }


    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setImageFile(File $file = null)
    {
        $this->imageFile = $file;

        if ($file) {
            $this->setCreationDate(new \DateTime('now'));
        }
    }


    /**
     * @return string
     */
    public function getImageUrl()
    {
        return "/images/memes/" . $this->imageName;
    }


    /**
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }


    /**
     * @param string $imageName
     *
     * @return Meme
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }


    /**
     * @return integer
     */
    public function getImageSize()
    {
        return $this->imageSize;
    }


    /**
     * @param integer $imageSize
     *
     * @return Meme
     */
    public function setImageSize($imageSize)
    {
        $this->imageSize = $imageSize;

        return $this;
    }


    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}