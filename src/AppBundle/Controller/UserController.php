<?php
/**
 * Author: tom
 * Date: 10.09.17
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    /**
     * @Route("/u/")
     * @Method("GET")
     */
    public function getCurrentUserAction()
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if (gettype($user) !== 'object') {
            return $this->json([]);
        }

        $response = [
            'id' => $user->getId(),
            'name' => $user->getUsername(),
            'email' => $user->getEmail(),
            'lastActionDate' => $user->getLastLogin()
        ];

        return $this->json($response);
    }


    /**
     * @Route("/u/")
     * @Method("POST")
     * @param Request $request
     * @return Response
     */
    public function createUserAction(Request $request)
    {
        $name = $request->request->get("name");
        $email = $request->request->get("email");
        $location = $request->request->get("location");

        $i18n = $this->get("translator");

        $response = [];

        if (!preg_match("/\b[a-zA-Z][a-zA-Z\d_-]{3,15}/", $name)) {
            $response['errors'][] = [
                'source' => 'name',
                'detail' => $i18n->trans('error.invalidUserName')
            ];
            return $this->json($response);
        }
        if (!preg_match("/\w+@\w+.[a-zA-Z]{2,}/", $email)) {
            $response['errors'][] = [
                'source' => 'email',
                'detail' => $i18n->trans('error.invalidEmail')
            ];
            return $this->json($response);
        }

        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findOneByUsername($name);
        if ($user) {
            $response['errors'][] = [
                'source' => 'name',
                'detail' => $i18n->trans('error.nameTaken')
            ];
            return $this->json($response);
        }

        $user = $repository->findOneByEmail($email);
        if ($user) {
            $response['errors'][] = [
                'source' => 'email',
                'detail' => $i18n->trans('error.emailAlreadyRegistered')
            ];
            return $this->json($response);
        }

        $password = bin2hex(openssl_random_pseudo_bytes(5));
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $user->setUsername($name);
        $user->setEmail($email);
        $user->setPassword($passwordHash);
        $user->setLocation($location);
        $user->setRegDate(new \DateTime());
        $em->persist($user);
        $em->flush();

        $fromAddress = $this->container->getParameter('mailer_address');
        $message = (new \Swift_Message($i18n->trans('register.mailSubject')))
            ->setFrom($fromAddress)
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'emails/registration.txt.twig',
                    array(
                        'name' => $name,
                        'password' => $password
                    )
                ),
                'text/plain'
            );
        $mailer = $this->get('mailer');
        $mailer->send($message);

        $response['success'] = $i18n->trans('register.success');
        return $this->json($response);
    }


    /**
     * @Route("/u/{name}/")
     * @Method({"GET"})
     * @param string $name
     * @return Response
     */
    public function getUserAction($name)
    {
        /** @var User $currentUser */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        $i18n = $this->get("translator");

        if (gettype($currentUser) !== 'object') {
            throw $this->createAccessDeniedException($i18n->trans('error.pleaseLogIn'));
        }

        $repository = $this->getDoctrine()->getRepository(User::class);
        /** @var User $user */
        $user = $repository->findOneByUsername($name);

        if ($user) {
            $response = [
                'id' => $user->getId(),
                'name' => $user->getUsername(),
                'lastActionDate' => $user->getLastLogin()
            ];

            return $this->json($response);
        }

        return $this->json([]);
    }


	/**
	 * @Route("/u/{id}/profile")
	 * @Method("GET")
	 * @param int $id
	 *
	 * @return Response
	 */
    public function getUserProfileAction($id)
    {
    	$repo = $this->getDoctrine()->getRepository(User::class);
    	/** @var User $user */
    	$user = $repo->find($id);

    	if (!$user) {
		    throw $this->createNotFoundException('The user does not exist');
	    }

	    return $this->render("user/profile.html.twig", [ 'user' => $user ]);
    }


    /**
     * @Route("/u/{id}")
     * @Method("PUT")
     * @param int $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateUserAction($id, Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        $i18n = $this->get("translator");

        if (gettype($currentUser) !== 'object') {
            throw $this->createAccessDeniedException($i18n->trans('error.pleaseLogIn'));
        }

        if ($currentUser->getId() != $id) {
            return $this->json([ "success" => false, "detail" => $i18n->trans('error.permissionDenied') ]);
        }

        $attributes = json_decode($request->getContent());

        $response = [];

        if (!password_verify($attributes->oldPassword, $currentUser->getPassword())) {
            $response["success"] = false;
            $response["errors"][] = [
                "source" => "oldPassword",
                "detail" => $i18n->trans('error.oldPasswordWrong')
            ];
            return $this->json($response);
        }

        if (strlen($attributes->newPassword) < 8) {
            $response["success"] = false;
            $response["errors"][] = [
                "source" => "newPassword",
                "detail" => $i18n->trans('error.passwordTooShort')
            ];
            return $this->json($response);
        }
        if (strlen($attributes->newPassword) > 32) {
            $response["success"] = false;
            $response["errors"][] = [
                "source" => "newPassword",
                "detail" => $i18n->trans('error.passwordTooLong')
            ];
            return $this->json($response);
        }

        $newPasswordHash = password_hash($attributes->newPassword, PASSWORD_BCRYPT);
        $currentUser->setPassword($newPasswordHash);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->json([ "success" => true]);
    }


    /**
     * @Route("/u/{id}")
     * @Method("DELETE")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteUserAction($id, Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        $i18n = $this->get('translator');

        if (gettype($currentUser) !== 'object') {
            throw $this->createAccessDeniedException($i18n->trans('error.pleaseLogIn'));
        }

        if ($currentUser->getId() != $id) {
            return $this->json([ "success" => false, "detail" => $i18n->trans('error.permissionDenied') ]);
        }

        $attributes = json_decode($request->getContent());

        if (!password_verify($attributes->password, $currentUser->getPassword())) {
            $response = [];
            $response["success"] = false;
            $response["errors"][] = [
                "source" => "password",
                "detail" => $i18n->trans('error.passwordWrong')
            ];
            return $this->json($response);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($currentUser);
        $em->flush();

        $request->getSession()->invalidate(1);

        return $this->json([ 'success' => true ]);
    }


	/**
	 * @Route("/u/avatar/")
	 * @Method("POST")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
    public function setAvatarAction(Request $request)
    {
	    $i18n = $this->get("translator");

	    /** @var User $currentUser */
	    $currentUser = $this->getUser();

	    if (gettype($currentUser) !== 'object') {
		    throw $this->createAccessDeniedException($i18n->trans('error.pleaseLogIn'));
	    }

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

		$currentUser->setAvatarFile($file);
	    $em = $this->getDoctrine()->getManager();
	    $em->persist($currentUser);
	    $em->flush();

    	return $this->json([ 'success' => true ]);
    }
}