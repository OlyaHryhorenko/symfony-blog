<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
	const ROLE_USER = 'ROLE_USER';
	const ROLE_ADMIN = 'ROLE_ADMIN';

	/**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;
	/**
	 * @ORM\Column(type="json")
	 */
	private $roles = [];

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="user")
	 */
	private $posts;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $enabled;

	public function __construct()
	{
		$this->posts = new ArrayCollection();
		$this->roles = [self::ROLE_USER];
		$this->enabled = false;
	}

	public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

	/**
	 * Returns the roles granted to the user.
	 *
	 *     public function getRoles()
	 *     {
	 *         return ['ROLE_USER'];
	 *     }
	 *
	 * Alternatively, the roles might be stored on a ``roles`` property,
	 * and populated in any number of different ways when the user object
	 * is created.
	 *
	 * @return (Role|string)[] The user roles
	 */
	public function getRoles(): array
	{
		$roles = $this->roles;
		// guarantee every user at least has ROLE_USER
		$roles[] = 'ROLE_USER';

		return array_unique($roles);
	}

	/**
	 * @param array $roles
	 */
	public function setRoles(array $roles): void
	{
		$this->roles = $roles;
	}

	/**
	 * @param mixed $enabled
	 */
	public function setEnabled($enabled): void
	{
		$this->enabled = $enabled;
	}

	/**
	 * Returns the salt that was originally used to encode the password.
	 *
	 * This can return null if the password was not encoded using a salt.
	 *
	 * @return string|null The salt
	 */
	public function getSalt() {
		// TODO: Implement getSalt() method.
	}


	/**
	 * Removes sensitive data from the user.
	 *
	 * This is important if, at any given point, sensitive information like
	 * the plain-text password is stored on this object.
	 */
	public function eraseCredentials() {
		// TODO: Implement eraseCredentials() method.
	}

	/**
	 * Returns the username used to authenticate the user.
	 *
	 * @return string The username
	 */
	public function getUsername() {
		// TODO: Implement getUsername() method.
	}

	/**
	 * @return mixed
	 */
	public function getPosts()
	{
		return $this->posts;
	}

}
