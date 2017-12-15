<?php
/**
 * Author: tom
 * Date: 04.12.17
 */

namespace AppBundle\Controller;

use AppBundle\Entity\BasePost;
use AppBundle\Entity\Vote;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class VoteController extends Controller {

	/**
	 * @Route("/m/{subjectId}/vote")
	 * @Method("POST")
	 * @param $subjectId int
	 * @return JsonResponse
	 */
	public function voteAction($subjectId, Request $request)
	{
		$repo = $this->getDoctrine()->getRepository(BasePost::class);
		$subject = $repo->find($subjectId);

		if (!$subject) {
			return $this->json("-");
		}

		$user = $this->getUser();
		if (!$user) {
			$score = $subject->getScore();
			return $this->json($score);
		}

		$repo = $this->getDoctrine()->getRepository(Vote::class);
		$votes = $repo->findBy([
			'subject' => $subject,
			'author'  => $user
		]);

		$em = $this->getDoctrine()->getManager();

		if (sizeof($votes) > 0) {
			foreach ($votes as $vote) {
				$em->remove($vote);
			}
		}

		$voteValue = $request->get('value');
		if (!$voteValue) {
			$score = $subject->getScore();
			return $this->json($score);
		}

		if ($voteValue > 0) {
			$voteValue = 1;
		} else {
			$voteValue = -1;
		}

		$newVote = new Vote();
		$newVote->setSubject($subject);
		$newVote->setAuthor($user);
		$newVote->setCreationDate(new \DateTime());
		$newVote->setCount($voteValue);

		$em->persist($newVote);
		$em->flush();

		$score = $subject->getScore();

		return $this->json($score);
	}


	/**
	 * @param $subjectId int
	 *
	 * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function _showAction($subjectId)
	{
		$repo = $this->getDoctrine()->getRepository(BasePost::class);
		$subject = $repo->find($subjectId);

		if (!$subject) {
			return $this->json("-");
		}

		$data = [
			'score'     => $subject->getScore(),
			'subjectId' => $subjectId
		];

		return $this->render("vote/vote.html.twig", [ 'data' => $data ]);
	}
}