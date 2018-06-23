<?php
/**
 * Author: tom
 * Date: 14.11.17
 */

namespace AppBundle\EventListener;

use Gregwar\Image\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Event\Event;

class ImageUploadListener
{
    /**
     * @param Event $event
     */
    public function convertToJpeg(Event $event)
    {
        $mapping = $event->getMapping();
        $object = $event->getObject();

        if ($mapping->getFile($object)->getMimeType() == "image/gif" ) {
        	return;
        }

        $path = Image::open($mapping->getFile($object)->getRealPath())
	        ->cropResize(1024,2048,0)
	        ->jpeg(95);
        $convertedImage = new UploadedFile($path, '', 'jpeg', 1, null, true);

        $mapping->setFile($object, $convertedImage);
    }
}