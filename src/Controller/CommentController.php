<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Like;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comments', name: 'comment_')]
class CommentController extends AbstractController
{

	private EntityManagerInterface $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	#[Route('/like/{id}', name: 'like')]
	public function likeCommentAction(Request $request, Comment $comment): JsonResponse
	{
		$user = $this->getUser();

		$like_exist = $this->em->getRepository(Like::class)->findOneBy([
			'comment' => $comment,
			'user' => $user
		]);

		if ($like_exist) {
			return new JsonResponse([
				'status' => 'error',
			]);
		}

		$like = new Like();
		$like->setUser($user);
		$like->setComment($comment);

		$this->em->persist($like);
		$this->em->flush();

		return new JsonResponse([
			'status' => 'ok',
		]);
	}
}
