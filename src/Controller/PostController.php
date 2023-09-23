<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
		dump($post);
		die();
		return $this->render('base.html.twig');
	}
}
