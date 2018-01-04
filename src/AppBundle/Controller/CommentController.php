<?php
/**
 * Author: tom
 * Date: 20.11.17
 */

namespace AppBundle\Controller;

use AppBundle\Entity\BasePost;
use AppBundle\Entity\Comment;
use AppBundle\Entity\User;
use AppBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class CommentController extends Controller
{
    /**
     * @Route("/m/{memeId}/c/")
     * @Method("POST")
     * @param int $memeId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction($memeId)
    {
        $i18n = $this->get("translator");

        /** @var User $currentUser */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        if (gettype($currentUser) !== 'object') {
            throw $this->createAccessDeniedException($i18n->trans('error.pleaseLogIn'));
        }

        $requestStack = $this->get('request_stack');
        $currentRequest = $requestStack->getCurrentRequest();

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->add('subject', HiddenType::class);
        $form->handleRequest($currentRequest);

        if ($form->isValid()) {
            /** @var Comment $comment */
            $comment = $form->getData();
            $comment->setCreationDate(new \DateTime());
            $comment->setAuthor($currentUser);

            $subjectId = $form->get('subject')->getData();
            $repo = $this->getDoctrine()->getRepository(BasePost::class);
            $subject = $repo->find($subjectId);
            if (!$subject) {
                throw $this->createNotFoundException('Invalid subject');
            }

            $comment->setSubject($subject);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $commentId = $comment->getId();

            return $this->redirect("/m/$memeId#comment$commentId");
        }

        return $this->redirect("/");
    }


    /**
     * @param integer $subjectId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function _commentFormAction($subjectId, $memeId)
    {
        $repository = $this->getDoctrine()->getRepository(BasePost::class);
        $subject = $repository->find($subjectId);
        if (!$subject) {
            throw $this->createNotFoundException('Invalid subject');
        }

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->add('subject', HiddenType::class, [ 'data' => $subjectId ]);

        return $this->render('comment/new.html.twig', [
        	'form' => $form->createView(),
	        'memeId' => $memeId
        ]);
    }


    public function _commentListAction($subjectId)
    {
        $repo = $this->getDoctrine()->getRepository(Comment::class);
        $comments = $repo->findBy([ 'subject' => $subjectId ], [ 'creationDate' => 'DESC' ] );

        $commentTree = [];

        foreach ($comments as $comment) {
	        $commentTree[] = $this->buildCommentTree( $comment );
        }

        return $this->render('comment/list.html.twig', [ 'comments' => $comments, 'tree' => $commentTree ]);
    }


	/**
	 * @param Comment $comment
	 *
	 * @return array
	 */
    private function buildCommentTree($comment) {
	    $tree = [];
	    $tree['comment'] = $comment;

    	$repo = $this->getDoctrine()->getRepository(Comment::class);
	    $comments = $repo->findBy([ 'subject' => $comment->getId() ], [ 'creationDate' => 'DESC' ] );

	    if (sizeof($comments) > 0) {
	    	$tree['children'] = [];
		    foreach ($comments as $c) {
			    $tree['children'][] = $this->buildCommentTree($c);
		    }
	    }

	    return $tree;
	}
}
