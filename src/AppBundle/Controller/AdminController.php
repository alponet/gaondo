<?php
/**
 * Author: tom
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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


	/**
	 * @Route("/support/")
	 * @Method("GET")
	 * @return Response
	 */
	public function supportForm()
	{
		return $this->render("admin/supportForm.html.twig");
	}



	/**
	 * @Route("/support/")
	 * @Method("POST")
	 * @param Request $request
	 * @return Response
	 */
	public function newSupportMessage(Request $request) {

		$name = $request->request->get("name");
		$email = $request->request->get("email");
		$subject = $request->request->get("subject");
		$message = $request->request->get("message");

		$response = [];

		if (strlen($email) < 6) {
			$response['errors'][] = [
				'source' => 'email',
				'detail' => 'Pifiaste con algo porque esa casilla de mail no va'
			];
			return $this->json($response);
		}

		if (strlen($message) < 6) {
			$response['errors'][] = [
				'source' => 'message',
				'detail' => 'Please give us some words!'
			];
			return $this->json($response);
		}

		$fromAddress = $this->container->getParameter('mailer_address');
		$message = (new \Swift_Message('[Gaondo] Contact form'))
			->setFrom($fromAddress)
			->setTo($this->container->getParameter('support_mail'))
			->setBody(
				"Boludo! Someone has sent us a message through the contact form. Here it is:\n\nname: $name\nmail: $email\nsubject: $subject\nmessage: $message",
				'text/plain'
			);
		$mailer = $this->get('mailer');
		$mailer->send($message);

		$response["success"] = true;
		return $this->json($response);
	}

}