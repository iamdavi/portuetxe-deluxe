<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

	private EntityManagerInterface $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	#[Route('/', name: 'homepage')]
	public function list(Request $request): Response
	{
		$posts = $this->em->getRepository(Post::class)->findBy(
			[],
			['createdAt' => 'DESC']
		);

		$comment = new Comment();
		$comment_form = $this->createForm(CommentType::class, $comment, [
			'addPost' => true
		]);

		$comment_form->handleRequest($request);
		if ($comment_form->isSubmitted() && $comment_form->isValid()) {

			$comment = $comment_form->getData();

			dump($comment);
			die();

			$this->em->persist($comment);
			$this->em->flush();

			return $this->redirectToRoute('homepage');
		}
		return $this->render('home/index.html.twig', [
			'posts' => $posts,
			'form' => $comment_form
		]);
	}
}
