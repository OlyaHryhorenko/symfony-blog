<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture {


	private const USERS = [
		[
			'login' => 'amelya',
			'email' => 'amelya@test.com',
			'password' => '0084172jkz',
			'roles' => [User::ROLE_ADMIN]
		],
		[
			'login' => 'rob_smith',
			'email' => 'rob_smith@smith.com',
			'password' => 'rob12345',
			'roles' => [User::ROLE_ADMIN]
		],
		[
			'login' => 'marry_gold',
			'email' => 'marry_gold@gold.com',
			'password' => 'marry12345',
			'roles' => [User::ROLE_ADMIN]
		],
		[
			'login' => 'super_admin',
			'email' => 'super_admin@admin.com',
			'password' => 'admin12345',
			'roles' => [User::ROLE_ADMIN]
		],
	];
	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $passwordEncoder;

	public function __construct(UserPasswordEncoderInterface $passwordEncoder)
	{
		$this->passwordEncoder = $passwordEncoder;
	}

	public function load(ObjectManager $manager)
	{
		$this->loadUsers($manager);
		$this->loadPosts($manager);
	}


	private function loadPosts(ObjectManager $manager)
	{
		for ($i = 0; $i < 10; $i++) {
			$post = new Post();
			$post->setTitle( 'This is title number '. $i);
			$post->setContent( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.' );
			$post->setSlug( 'slug-'.$i);
			$post->getPublishedAt();
			$post->setUser($this->getReference(
				self::USERS[rand(0, count(self::USERS) - 1)]['login']
			));
			$manager->persist( $post );
		}

		$manager->flush();
	}

	private function loadUsers(ObjectManager $manager)
	{
		foreach (self::USERS as $userData) {
			$user = new User();
			$user->setLogin($userData['login']);
			$user->setEmail($userData['email']);
			$user->setPassword(
				$this->passwordEncoder->encodePassword(
					$user,
					$userData['password']
				)
			);
			$user->setRoles($userData['roles']);
			$user->setEnabled(true);
			$this->addReference(
				$userData['login'],
				$user
			);

			$manager->persist($user);
		}

		$manager->flush();
	}

}
