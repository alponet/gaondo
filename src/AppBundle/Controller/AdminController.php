<?php
/**
 * Author: tom
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{

	/**
	 * @Route("/admin/")
	 * @Method("GET")
	 * @return Response
	 */
	public function adminIndex()
	{
		return $this->render("admin/index.html.twig");
	}

}