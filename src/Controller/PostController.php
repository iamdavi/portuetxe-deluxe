<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Like;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/posts', name: 'post_')]
class PostController extends AbstractController
{

	private EntityManagerInterface $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	#[Route('/crear', name: 'new')]
	public function newPostAction(Request $request): Response
	{
		$post = new Post();

		$form = $this->createForm(PostType::class, $post);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$post = $form->getData();
			$post->setUser($this->getUser());

			$this->em->persist($post);
			$this->em->flush();

			return $this->redirectToRoute('homepage');
		}

		return $this->render('post/new.html.twig', [
			'form' => $form
		]);
	}

	#[Route('/{id}/ver', name: 'view')]
	public function viewPostAction(Request $request, Post $post): Response
	{
		$comment = new Comment();
		$comment_form = $this->createForm(CommentType::class, $comment);

		$comment_form->handleRequest($request);
		if ($comment_form->isSubmitted() && $comment_form->isValid()) {

			$comment = $comment_form->getData();
			$comment->setPost($this->getUser());

			$this->em->persist($comment);
			$this->em->flush();

			return $this->redirectToRoute('homepage');
		}

		return $this->render('post/view.html.twig', [
			'post' => $post,
			'comment_form' => $comment_form
		]);
	}

	#[Route('/like/{id}', name: 'like')]
	public function likePostAction(Request $request, Post $post): JsonResponse
	{
		$user = $this->getUser();

		$like_exist = $this->em->getRepository(Like::class)->findOneBy([
			'post' => $post,
			'user' => $user
		]);

		if ($like_exist) {
			return new JsonResponse([
				'status' => 'error',
			]);
		}

		$like = new Like();
		$like->setUser($user);
		$like->setPost($post);

		$this->em->persist($like);
		$this->em->flush();

		return new JsonResponse([
			'status' => 'success',
		]);
	}
}
