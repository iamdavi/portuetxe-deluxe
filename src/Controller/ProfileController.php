<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\File\File;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/perfil', name: 'profile_')]
class ProfileController extends AbstractController
{

	private EntityManagerInterface $em;
	private FileUploader $fileUploader;

	public function __construct(
		EntityManagerInterface $em,
		FileUploader $fileUploader
	) {
		$this->em = $em;
		$this->fileUploader = $fileUploader;
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
			/** @var UploadedFile $avatarFile */
			$avatarFile = $form->get('avatar')->getData();
			if ($avatarFile) {
				$avatarFilename = $this->fileUploader->upload($avatarFile);

				$profile->setAvatarFilename($avatarFilename);
			}
			$this->em->flush();
			return $this->redirectToRoute('profile_view');
		}

		return $this->render('profile/edit.html.twig', [
			'form' => $form
		]);
	}

	#[Route('/{id}/view', name: 'user_profile')]
	public function viewUserProfileAction(Request $request): Response
	{
		$profile = $this->getUser()->getProfile();

		dump($profile);
		die();

		return $this->render('profile/edit.html.twig', []);
	}
}
