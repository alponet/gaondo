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
     * ToDo: will this be index or self/login?
     *
     * @Route("/u/")
     * @Method("GET")
     */
    public function indexAction() {
        return $this->render('user/index.html.twig');
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

        $response = new Response();

        if (!preg_match("/\b[a-zA-Z][a-zA-Z\d_-]{3,15}/", $name)) {
            $response->setContent('Invalid user name!');
            return $response;
        }
        if (!preg_match("/\w+@\w+.[a-zA-Z]{2,}/", $email)) {
            $response->setContent('Invalid email address');
            return $response;
        }

        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findOneByName($name);
        if ($user) {
            $response->setContent('Name already taken.');
            return $response;
        }

        $user = $repository->findOneByEmail($email);
        if ($user) {
            $response->setContent('Email address is already registered');
            return $response;
        }

        $password = bin2hex(openssl_random_pseudo_bytes(5));
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $user->setName($name);
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

        $response->setContent('Please check your email.');
        return $response;
    }


    /**
     * @Route("/u/{name}/")
     * @Method({"GET"})
     * @return Response
     */
    public function userAction($name) {
        return $this->render('user/profile.html.twig', array(
            'name' => $name
        ));
    }
}