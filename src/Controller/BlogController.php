<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BlogController extends AbstractController
{
	/**
	 * @var PostRepository
	 */
	private $postRepository;
	/**
	 * @var FormFactoryInterface
	 */
	private $formFactory;
	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;
	/**
	 * @var RouterInterface
	 */
	private $router;
	/**
	 * @var FlashBagInterface
	 */
	private $flashBag;

	/**
	 * @param MicroPostRepository $microPostRepository
	 * @param FormFactoryInterface $formFactory
	 * @param EntityManagerInterface $entityManager
	 * @param RouterInterface $router
	 */
	public function __construct(
		PostRepository $postRepository,
		FormFactoryInterface $formFactory,
		EntityManagerInterface $entityManager, RouterInterface $router,
		FlashBagInterface $flashBag
	) {
		$this->postRepository = $postRepository;
		$this->formFactory = $formFactory;
		$this->entityManager = $entityManager;
		$this->router = $router;
		$this->flashBag = $flashBag;
	}

	/**
     * @Route("/", name="main")
     */
    public function index()
    {
	    $posts = $this->getDoctrine()
	                      ->getRepository(Post::class)->findAll();
	    return $this->render('posts.html.twig', [
	    	'posts' => $posts
	    ]);
    }

	/**
	 * @Route("/add", name="post_add")
	 * @param Request $request
	 * @param TokenStorageInterface $tokenStorage
	 *
	 * @return RedirectResponse|Response
	 */
	public function add(Request $request, TokenStorageInterface $tokenStorage )
	{
		$post = new Post();
		$user = $tokenStorage->getToken()
		                     ->getUser();
		$post->setUser($user);

		$form = $this->formFactory->create(
			PostType::class,
			$post
		);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			/** @var UploadedFile $thumbnail */
			$thumbnail = $form->get('thumbnail')->getData();

			// this condition is needed because the 'brochure' field is not required
			// so the PDF file must be processed only when a file is uploaded
			if ($thumbnail) {
				$originalFilename = pathinfo( $thumbnail->getClientOriginalName(), PATHINFO_FILENAME );
				// this is needed to safely include the file name as part of the URL
				$safeFilename = transliterator_transliterate( 'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename );
				$newFilename  = $safeFilename . '-' . uniqid() . '.' . $thumbnail->guessExtension();

				// Move the file to the directory where brochures are stored
				try {
					$thumbnail->move(
						$this->getParameter( 'thumbnail_directory' ),
						$newFilename
					);
				} catch ( FileException $e ) {

				}
			}

				// updates the 'brochureFilename' property to store the PDF file name
				// instead of its contents
			if ($thumbnail) {
				$post->setThumbnail($newFilename);
			}

			$this->entityManager->persist($post);
			$this->entityManager->flush();
			$this->addFlash('success', 'Article Created! Knowledge is power!');

			return new RedirectResponse(
				$this->router->generate('post_add')
			);
		}


		return $this->render(
			'add.html.twig',
			['form' => $form->createView()]
		);
	}

	/**
	 * Finds and displays a App entity.
	 *
	 * @Route("/post/{id}", name="show_post")
	 */
	public function show($id)
	{
		$em = $this->getDoctrine()->getManager();

		$post = $this->getDoctrine()
		              ->getRepository(Post::class)->find($id);

		return $this->render('post.html.twig', [
			'post' => $post
		]);
	}


	/**
	 * Finds and displays a App entity.
	 *
	 * @Route("/edit/{id}", name="edit_post")
	 */
	public function edit(Request $request, $id)
	{

		$em = $this->getDoctrine()->getManager();

		$post = $this->getDoctrine()
		             ->getRepository(Post::class)->find($id);


		$form = $this->formFactory->create(
			PostType::class,
			$post
		);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->entityManager->persist($post);
			$this->entityManager->flush();
			$this->addFlash('success', 'Article Created! Knowledge is power!');

			return $this->redirect($request->headers->get('referer'));
		}


		return $this->render(
			'add.html.twig',
			['form' => $form->createView()]
		);
	}

}
