<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
	public function list(): Response
	{
		$posts = $this->em->getRepository(Post::class)->findBy(
			[],
			['createdAt' => 'DESC']
		);
		return $this->render('home/index.html.twig', [
			'posts' => $posts
		]);
	}
}
