<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistationController extends AbstractController
{
	/**
	 * @Route("/register", name="user_registration")
	 * @param Request $request
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
	{
		// 1) build the form
		$user = new User();
		$form = $this->createForm(UserType::class, $user);

		// 2) handle the submit (will only happen on POST)
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			// 3) Encode the password (you could also do this via Doctrine listener)
			$password = $passwordEncoder->encodePassword($user, $user->getPassword());
			$user->setPassword($password);

			// 4) save the User!
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($user);
			$entityManager->flush();

			// ... do any other work - like sending them an email, etc
			// maybe set a "flash" success message for the user

			return $this->redirectToRoute('main');
		}
		$error = [];
		return $this->render('security/registration.html.twig', ['form' => $form->createView(),'error' => $error]);
	}
}
