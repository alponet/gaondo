<?php
/**
 * Author: tom
 * Date: 08.10.17
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Meme;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class MemeController extends Controller
{

    /**
     * @Route("/m/")
     * @Method("GET")
     * @return Response
     */
    public function getMemesAction()
    {
        $repository = $this->getDoctrine()->getRepository(Meme::class);

        /** @var Meme[] $memes */
        $memes = $repository->findAll();

        if ($memes) {
            $response = [];

            foreach ($memes as $meme) {
                $response[] = [
                    "id"    => $meme->getId(),
                    "title" => $meme->getTitle(),
                    "file"  => "/memes/" . $meme->getFile(),
                    "description" => $meme->getDescription(),
                    "author"    =>  $meme->getAuthor()->getUsername(),
                    "date" => $meme->getCreationDate()
                ];
            }

            return $this->json($response);
        }

        return $this->json([]);
    }
}