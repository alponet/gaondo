<?php
/**
 * Author: tom
 * Date: 10.09.17
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * ToDo: will this be index or self/login?
     *
     * @Route("/u")
     */
    public function indexAction() {
        return $this->render('user/index.html.twig');
    }

    /**
     * @Route("/u/{name}")
     * @return Response
     */
    public function userAction($name) {
        return $this->render('user/profile.html.twig', array(
            'name' => $name
        ));
    }
}