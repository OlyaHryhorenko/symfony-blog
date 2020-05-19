<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=App\Repository\PostRepository::class)
 */
class Post {
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $title;
	/**
	 * @ORM\Column(type="string", length=100)
	 */
	private $slug;
	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $content;
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $publishedAt;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $user;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $thumbnail;

	/**
	 * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="posts")
	 */
	private $categories;

	public function __construct() {
		$this->categories = new ArrayCollection();
	}

	public function getId(): ?int {
		return $this->id;
	}

	public function getTitle(): ?string {
		return $this->title;
	}

	public function setTitle( string $title ): self {
		$this->title = $title;

		return $this;
	}

	public function getSlug(): ?string {
		return $this->slug;
	}

	public function setSlug( string $slug ): self {
		$this->slug = $slug;

		return $this;
	}

	public function getContent(): ?string {
		return $this->content;
	}

	public function setContent( ?string $content ): self {
		$this->content = $content;

		return $this;
	}

	public function getPublishedAt(): ?\DateTimeInterface {
		return $this->publishedAt;
	}

	public function setPublishedAt( ?\DateTimeInterface $publishedAt ): self {
		$this->publishedAt = $publishedAt;

		return $this;
	}

	/**
	 * @return User
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @param mixed $user
	 */
	public function setUser($user): void
	{
		$this->user = $user;
	}

	public function getThumbnail()
	{
		return $this->thumbnail;
	}

	public function setThumbnail($thumbnail) {
		$this->thumbnail = $thumbnail;

		return $this;
	}

	/**
	 * @return Collection
	 */
	public function getCategories(): Collection
	{
		return $this->categories;
	}
	public function addCategory(Category $category): self
	{
		if (!$this->categories->contains($category)) {
			$this->categories[] = $category;
		}
		return $this;
	}
	public function removeCategory(Category $category): self
	{
		if ($this->categories->contains($category)) {
			$this->categories->removeElement($category);
		}
		return $this;
	}

	public function setCategories( $category ) {
		$this->categories = $category;

		return $this;
	}
}
