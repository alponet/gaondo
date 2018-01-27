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
	 * @Route("/", name="stream")
	 * @return Response
	 */
	public function indexAction()
	{
		$repo = $this->getDoctrine()->getRepository(Meme::class);
		$memes = $repo->findBy([], ['creationDate' => 'DESC'], 5);

		return $this->render('meme/index.html.twig', [ 'memes' => $memes ]);
	}


    /**
     * @Route("/m/")
     * @Method("GET")
     * @return Response
     */
    public function getMemesAction(Request $request)
    {
    	$offset = $request->query->get("offset");
    	$limit = $request->query->get("limit");
    	$author = $request->query->get("author");

    	$filter = [];
    	if ($author) {
    		$filter["author"] = $author;
	    }

        $repository = $this->getDoctrine()->getRepository(Meme::class);

        /** @var Meme[] $memes */
        $memes = $repository->findBy($filter, ['creationDate' => 'DESC'], $limit, $offset);

        if ($memes) {
            $response = [];

            foreach ($memes as $meme) {
            	/*
                $response[] = [
                    "id"    => $meme->getId(),
                    "title" => $meme->getTitle(),
                    "file"  => "images/memes/" . $meme->getImageName(),
                    "description" => $meme->getDescription(),
                    "author"    =>  $meme->getAuthor()->getUsername(),
                    "date" => $meme->getCreationDate()
                ];
            	*/

	            $mId = "m" . $meme->getId();

            	$memeData = [];
            	$memeData["descriptor"] = $mId;
            	$memeData["element"] = '<div id="' . $mId .'" class="meme">'
		            . $this->renderView(":meme:single.html.twig", [ 'meme' => $meme, 'link' => true ] )
		            . '</div>';

            	$response[] = $memeData;
            }

            return $this->json($response);
        }

        return $this->json([]);
    }


    /**
     * @Route("/m/{id}")
     * @param int $id
     * @return Response
     */
    public function getMemeAction($id)
    {
        $repository = $this->getDoctrine()->getRepository(Meme::class);

        $meme = $repository->find($id);

        if (!$meme) {
            throw $this->createNotFoundException('Meme not found!');
        }

        return $this->render('meme/meme.html.twig', [
            'meme' => $meme
        ]);
    }


	/**
	 * @Route("/newMeme/")
	 * @return Response
	 */
    public function newMemeAction()
    {
    	return $this->render('meme/new.html.twig');
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
        $currentUser = $this->getUser();

        if (gettype($currentUser) !== 'object') {
            throw $this->createAccessDeniedException($i18n->trans('error.pleaseLogIn'));
        }

        $title = $request->request->get("title");
        $description = $request->request->get("description");

        /** @var UploadedFile $file */
        $file = $request->files->get("file");

        if (!$file) {
            $response= [];
            $response["errors"][] = [
                "source" => "file",
                "detail" => $i18n->trans('error.invalidFile')
            ];
            return $this->json($response);
        }

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

        if (strlen($title) < 3) {
            $response = [];
            $response["errors"][] = [
                "source" => "title",
                "detail" => $i18n->trans('error.titleTooShort')
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

        $response = [];
        $response["memeId"] = $meme->getId();

        return $this->json($response);
    }
}