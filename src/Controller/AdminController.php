<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/profile", name="admin")
     */
    public function index()
    {
		$id = $this->getUser()->getId();
	    $em = $this->getDoctrine()->getManager();

	    $user = $this->getDoctrine()
	                    ->getRepository(User::class)
	                    ->find($id);
	    $posts = $this->getDoctrine()
	                 ->getRepository(Post::class)
		    ->findBy(
			    array('user'=> $user),
			    array('id' => 'ASC')
		    );

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
	        'posts' => $posts
        ]);
    }

	/**
	 * @Route("/user", name="admin")
	 */
	public function user() {
		return $this->render('admin/user.html.twig', [
		]);
	}

}
