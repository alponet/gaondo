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
        $i18n = $this->get("translator");

        /** @var User $currentUser */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        if (gettype($currentUser) !== 'object') {
            throw $this->createAccessDeniedException($i18n->trans('error.pleaseLogIn'));
        }

        $title = $request->request->get("title");
        $description = $request->request->get("description");

        if (strlen($title) < 3) {
            $response = [];
            $response["errors"][] = [
                "source" => "title",
                "detail" => $i18n->trans('error.titleTooShort')
            ];
            return $this->json($response);
        }

        /** @var UploadedFile $file */
        $file = $request->files->get("file");

        $fileSize = $file->getClientSize();

        if ($fileSize < 1 || $fileSize > 8388607) {
            $response= [];
            $response["errors"][] = [
                "source" => "file",
                "detail" => $i18n->trans('error.fileTooBig')
            ];
            return $this->json($response);
        }

        $mimeType = $file->getMimeType();

        if (!preg_match("/^image\/[a-z]+$/", $mimeType)) {
            $response = [];
            $response["success"] = false;
            $response["errors"][] = [
                "source" => "file",
                "detail" => $i18n->trans('error.invalidImageFile')
            ];
            return $this->json($response);
        }

        $meme = new Meme();
        $meme->setAuthor($currentUser);
        $meme->setCreationDate(new \DateTime());
        $meme->setTitle($title);
        $meme->setImageFile($file);
        $meme->setDescription($description);
        $meme->setImageSize(0);

        $em = $this->getDoctrine()->getManager();
        $em->persist($meme);
        $em->flush();

        return $this->json($meme);
    }
}