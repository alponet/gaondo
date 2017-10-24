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
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
        $memes = $repository->findBy([], ['creationDate' => 'DESC']);

        if ($memes) {
            $response = [];

            foreach ($memes as $meme) {
                $response[] = [
                    "id"    => $meme->getId(),
                    "title" => $meme->getTitle(),
                    "file"  => "images/memes/" . $meme->getImageName(),
                    "description" => $meme->getDescription(),
                    "author"    =>  $meme->getAuthor()->getUsername(),
                    "date" => $meme->getCreationDate()
                ];
            }

            return $this->json($response);
        }

        return $this->json([]);
    }


    /**
     * @Route("/m/")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createMemeAction(Request $request)
    {
        $logger = $this->get("logger");
        $i18n = $this->get("translator");

        /** @var User $currentUser */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        if (gettype($currentUser) !== 'object') {
            throw $this->createAccessDeniedException($i18n->trans('error.pleaseLogIn'));
        }

        // ToDo: Validation
        // max File size?

        $title = $request->request->get("title");

        /** @var UploadedFile $file */
        $file = $request->files->get("file");

        $description = $request->request->get("description");

        $fileName = $file->getClientOriginalName();
        $logger->info($fileName);

        $fileSize = $file->getClientSize();
        $logger->info($fileSize);

        $meme = new Meme();
        $meme->setAuthor($currentUser);
        $meme->setCreationDate(new \DateTime());
        $meme->setTitle($title);
        $meme->setImageFile($file);
        $meme->setDescription($description);
        $meme->setImageSize($fileSize);

        $em = $this->getDoctrine()->getManager();
        $em->persist($meme);
        $em->flush();

        return $this->json($meme);
    }
}