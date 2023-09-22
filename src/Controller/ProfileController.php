<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/perfil', name: 'profile_')]
class ProfileController extends AbstractController
{

	private EntityManagerInterface $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	#[Route('/', name: 'view')]
	public function viewProfileAction(Request $request): Response
	{
		$profile = $this->getUser()->getProfile();

		return $this->render('profile/view.html.twig', [
			'profile' => $profile
		]);
	}

	#[Route('/editar', name: 'edit')]
	public function editProfileAction(Request $request): Response
	{
		$profile = $this->getUser()->getProfile();

		$form = $this->createForm(ProfileType::class, $profile);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$profile = $form->getData();

			$this->em->flush();
			return $this->redirectToRoute('profile_view');
		}

		return $this->render('profile/edit.html.twig', [
			'form' => $form
		]);
	}
}
