<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

	/**
	 * @ORM\Column(type="text")
	 */
	private $description;

	/**
	 * @ORM\ManyToMany(targetEntity="App\Entity\Post", mappedBy="categories")
	 */
	private $posts;

	public function __construct()  {
		$this->posts = new ArrayCollection();
	}

	/**
	 * @return mixed
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param mixed $description
	 */
	public function setDescription( $description ): void {
		$this->description = $description;
	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


	/**
	 * @return Collection|Post[]
	 */
	public function getPosts(): Collection
	{
		return $this->posts;
	}
	public function addPost(Post $post): self
	{
		if (!$this->posts->contains($post)) {
			$this->posts[] = $post;
			$post->addCategory($this);
		}
		return $this;
	}

	public function removeArticle(Post $post): self
	{
		if ($this->posts->contains($post)) {
			$this->posts->removeElement($post);
			$post->removeTag($this);
		}
		return $this;
	}
}
