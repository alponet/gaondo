<?php
/**
 * Author: tom
 * Date: 10.09.17
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    /**
     * @Route("/u/")
     * @Method("GET")
     */
    public function getCurrentUserAction() {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if (gettype($user) !== 'object') {
            return $this->json([]);
        }

        $response = [
            'id' => $user->getId(),
            'name' => $user->getUsername(),
            'email' => $user->getEmail(),
            'registrationDate' => $user->getRegDate(),
            'lastActionDate' => $user->getActiveDate()
        ];

        return $this->json($response);
    }


    /**
     * @Route("/u/")
     * @Method("POST")
     * @param Request $request
     * @return Response
     */
    public function createUserAction(Request $request) {
        $name = $request->request->get("name");
        $email = $request->request->get("email");

        $response = [];

        if (!preg_match("/\b[a-zA-Z][a-zA-Z\d_-]{3,15}/", $name)) {
            $response['errors'][] = [
                'source' => 'name',
                'detail' => 'Invalid user name!'
            ];
            return $this->json($response);
        }
        if (!preg_match("/\w+@\w+.[a-zA-Z]{2,}/", $email)) {
            $response['errors'][] = [
                'source' => 'email',
                'detail' => 'Invalid email address!'
            ];
            return $this->json($response);
        }

        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findOneByUsername($name);
        if ($user) {
            $response['errors'][] = [
                'source' => 'name',
                'detail' => 'Name already taken.'
            ];
            return $this->json($response);
        }

        $user = $repository->findOneByEmail($email);
        if ($user) {
            $response['errors'][] = [
                'source' => 'email',
                'detail' => 'Email address is already registered.'
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
        $user->setRegDate(new \DateTime());
        $em->persist($user);
        $em->flush();

        $message = (new \Swift_Message('Your gato peroncho login'))
            ->setFrom('no-reply@alpers.it')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'Emails/registration.txt.twig',
                    array(
                        'name' => $name,
                        'password' => $password
                    )
                ),
                'text/plain'
            );
        $mailer = $this->get('mailer');
        $mailer->send($message);

        $response['success'] = 'Please check your email.';
        return $this->json($response);
    }


    /**
     * @Route("/u/{name}/")
     * @Method({"GET"})
     * @return Response
     */
    public function getUserAction($name) {
        /** @var User $currentUser */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        if (gettype($currentUser) !== 'object') {
            throw $this->createAccessDeniedException('please log in');
        }

        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findOneByUsername($name);

        if ($user) {
            $response = [
                'id' => $user->getId(),
                'name' => $user->getUsername(),
                'email' => $user->getEmail(),
                'registrationDate' => $user->getRegDate(),
                'lastActionDate' => $user->getActiveDate()
            ];

            return $this->json($response);
        }

        return $this->json([]);
    }
}